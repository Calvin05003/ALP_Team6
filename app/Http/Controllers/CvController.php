<?php

namespace App\Http\Controllers;

use App\Models\CvAnalysis;
use App\Models\CvSubmission;
use App\Services\CvAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;

class CvController extends Controller
{
    protected CvAnalysisService $cvAnalysisService;

    public function __construct(CvAnalysisService $cvAnalysisService)
    {
        $this->cvAnalysisService = $cvAnalysisService;
    }

    public function create()
    {
        return view('cv.upload');
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'input_mode' => 'required|in:file,manual',
            'cv_file' => 'required_if:input_mode,file|file|mimes:pdf,doc,docx,txt|max:5120',
            'cv_text' => 'required_if:input_mode,manual|string',
        ]);

        $inputMode = $request->input('input_mode');

        $originalFilename = null;
        $storedPath = null;
        $extractedText = null;

        // 2. Ambil teks CV berdasarkan mode input
        if ($inputMode === 'file' && $request->hasFile('cv_file')) {
            /** @var UploadedFile $file */
            $file = $request->file('cv_file');

            $originalFilename = $file->getClientOriginalName();
            // Simpan di storage/app/public/cv
            $storedPath = $file->store('cv', 'public');

            // Panggil helper untuk ekstraksi teks
            $extractedText = $this->extractTextFromUploadedFile($file);
        } else {
            // input manual
            $originalFilename = 'manual-input-' . now()->timestamp . '.txt';
            $storedPath = null;
            $extractedText = $request->input('cv_text');
        }

        // Safety fallback - pastikan teks ada
        if (empty($extractedText)) {
            return back()
                ->withErrors(['cv_file' => 'Gagal membaca isi CV. Pastikan format file benar atau implementasi text extractor di controller belum lengkap.'])
                ->withInput();
        }

        // 3. Simpan ke tabel cv_submissions
        $cvSubmission = CvSubmission::create([
            'user_id' => auth()->id(), // boleh null kalau belum pakai auth
            'original_filename' => $originalFilename,
            'stored_path' => $storedPath,
            'extracted_text' => $extractedText,
            'input_mode' => $inputMode,
        ]);

        // 4. Kirim ke AI lewat service
        try {
            $result = $this->cvAnalysisService->analyze($extractedText);
        } catch (\Throwable $e) {
            // Pesan error dari Gemini/cURL akan lebih jelas berkat perbaikan di service
            return back()
                ->withErrors(['ai' => 'Terjadi kesalahan saat memproses CV di AI: ' . $e->getMessage()])
                ->withInput();
        }

        /**
         * Normalisasi hasil dari service supaya punya struktur yang konsisten:
         *
         * Kita HARUS mengisi key:
         * - resume_score
         * - main_skills
         * - division_recommendations
         * - readiness_scores
         * - skill_gaps
         * - feedback
         */

        // Kalau service sudah mengembalikan langsung struktur ini, tinggal pakai.
        // Tapi kalau dia pakai wrapper seperti ["analysis" => [...]
        if (isset($result['analysis']) && is_array($result['analysis'])) {
            $analysisRaw = $result['analysis'];

            $normalized = [
                'resume_score' => $analysisRaw['resume_score'] ?? 0,
                'main_skills' => $analysisRaw['main_skills_and_experiences'] ?? [],
                'division_recommendations' => [],
                'readiness_scores' => [],
                'skill_gaps' => [],
                'feedback' => '',
            ];

            // mapping division_matches → 3 field terpisah
            if (!empty($analysisRaw['division_matches']) && is_array($analysisRaw['division_matches'])) {
                foreach ($analysisRaw['division_matches'] as $match) {
                    $divisionName = $match['division_name'] ?? 'Unknown';

                    $normalized['division_recommendations'][] = [
                        'division_name' => $divisionName,
                        'reason' => $match['reason'] ?? '',
                    ];

                    $normalized['readiness_scores'][] = [
                        'division_name' => $divisionName,
                        'score' => $match['readiness_score'] ?? 0,
                    ];

                    $normalized['skill_gaps'][] = [
                        'division_name' => $divisionName,
                        'missing_skills' => $match['skill_gaps'] ?? [],
                    ];
                }
            }

            // gabungkan feedback jadi satu string panjang yang enak dibaca
            if (!empty($analysisRaw['feedback']) && is_array($analysisRaw['feedback'])) {
                $feedbackParts = [];

                foreach ($analysisRaw['feedback'] as $sectionTitle => $items) {
                    if (is_array($items) && count($items) > 0) {
                        $feedbackParts[] = strtoupper(str_replace('_', ' ', $sectionTitle)) . ':';
                        foreach ($items as $item) {
                            $feedbackParts[] = '- ' . $item;
                        }
                        $feedbackParts[] = ''; // baris kosong pemisah
                    }
                }

                $normalized['feedback'] = implode("\n", $feedbackParts);
            }

            $result = $normalized;
        }

        // 5. Simpan hasil analisis ke cv_analyses
        $analysis = CvAnalysis::create([
            'cv_submission_id' => $cvSubmission->id,
            'resume_score' => $result['resume_score'] ?? 0,

            // encode ke JSON string sebelum simpan
            'main_skills_json' => json_encode($result['main_skills'] ?? []),
            'division_recommendations_json' => json_encode($result['division_recommendations'] ?? []),
            'skill_gap_json' => json_encode($result['skill_gaps'] ?? []),
            'readiness_scores_json' => json_encode($result['readiness_scores'] ?? []),

            'feedback_text' => $result['feedback'] ?? null,
            'raw_ai_response' => json_encode($result),
        ]);

        // 6. Redirect ke halaman hasil
        return redirect()
            ->route('cv.result', $analysis->id)
            ->with('success', 'CV berhasil dianalisis.');
    }

    public function result($id)
    {
        $analysis = CvAnalysis::with('cvSubmission')->findOrFail($id);
        return view('cv.result', compact('analysis'));
    }

    /**
     * Helper untuk ekstrak text dari file upload.
     * Mendukung: TXT, PDF, DOC, DOCX.
     */
    protected function extractTextFromUploadedFile(UploadedFile $file): ?string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filePath = $file->getRealPath();

        try {
            $text = '';

            // 1) TXT – paling simpel
            if ($extension === 'txt') {
                $text = file_get_contents($filePath) ?: '';
            }

            // 2) PDF – pakai smalot/pdfparser
            elseif ($extension === 'pdf') {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText() ?? '';
            }

            // 3) DOCX – pakai PhpOffice\PhpWord
            elseif ($extension === 'docx') {
                $phpWord = WordIOFactory::load($filePath, 'Word2007');
                $text = $this->extractTextFromPhpWord($phpWord);
            }

            // 4) DOC (format lama)
            elseif ($extension === 'doc') {
                // MsDoc reader (bisa gagal untuk beberapa dokumen lama, tapi ini best effort)
                $phpWord = WordIOFactory::load($filePath, 'MsDoc');
                $text = $this->extractTextFromPhpWord($phpWord);
            }

            // Format lain tidak didukung
            else {
                return null;
            }

            // Bersihkan & normalisasi teks
            $text = $this->normalizeCvText($text);

            // Kalau setelah dibersihkan masih kosong, anggap gagal
            if (trim($text) === '') {
                return null;
            }

            return $text;
        } catch (\Throwable $e) {
            \Log::error('CV text extraction failed', [
                'extension' => $extension,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Ekstrak teks dari objek PhpWord (DOC/DOCX).
     */
    protected function extractTextFromPhpWord($phpWord): string
    {
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                // Banyak elemen (TextRun, Text, ListItem, dsb.)
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                } elseif (method_exists($element, 'getElements')) {
                    foreach ($element->getElements() as $child) {
                        if (method_exists($child, 'getText')) {
                            $text .= $child->getText() . "\n";
                        }
                    }
                }
            }
        }

        return $text;
    }

    /**
     * Normalisasi teks CV supaya lebih bersih sebelum dikirim ke AI.
     */
    protected function normalizeCvText(string $text): string
    {
        // Pastikan encoding UTF-8
        if (!mb_detect_encoding($text, 'UTF-8', true)) {
            $text = mb_convert_encoding($text, 'UTF-8');
        }

        // Hapus karakter kontrol aneh
        $text = preg_replace('/[^\PC\s]/u', '', $text);

        // Samakan line break
        $text = preg_replace("/\r\n|\r/", "\n", $text);

        // Hapus spasi/tab berlebihan
        $text = preg_replace("/[ \t]+/", ' ', $text);

        // Maksimal 2 newline berturut-turut
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim($text);
    }
}