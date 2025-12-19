<?php

namespace App\Http\Controllers;

use App\Models\CvAnalysis;
use App\Models\CvSubmission;
use App\Services\CvAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth; // [FIX] Tambahkan ini untuk Auth::id()
use Illuminate\Support\Facades\Log;  // [FIX] Tambahkan ini untuk Log::error()
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
        // Susun rules dasar
        $rules = [
            'input_mode' => 'required|in:file,manual',
            'cv_file'    => 'required_if:input_mode,file|file|mimes:pdf,doc,docx,txt|max:5120',
        ];

        // Kalau mode manual, baru validasi cv_text
        if ($request->input('input_mode') === 'manual') {
            $rules['cv_text'] = 'required|string';
        }

        // Jalankan validasi
        $validated = $request->validate($rules);

        $inputMode = $validated['input_mode'];

        $originalFilename = null;
        $storedPath       = null;
        $extractedText    = null;

        // 2. Ambil teks CV berdasarkan mode input
        if ($inputMode === 'file' && $request->hasFile('cv_file')) {
            /** @var UploadedFile $file */
            $file = $request->file('cv_file');

            $originalFilename = $file->getClientOriginalName();
            // Simpan di storage/app/public/cv
            $storedPath = $file->store('cv', 'public');

            // Panggil helper untuk ekstraksi teks (PDF/DOC/DOCX/TXT)
            $extractedText = $this->extractTextFromUploadedFile($file);
        } else {
            // input manual
            $originalFilename = 'manual-input-' . now()->timestamp . '.txt';
            $storedPath       = null;
            $extractedText    = $validated['cv_text'];
        }

        // Safety fallback - pastikan teks ada
        if (empty($extractedText)) {
            return back()
                ->withErrors(['cv_file' => 'Gagal membaca isi CV. Pastikan format file benar atau implementasi text extractor di controller belum lengkap.'])
                ->withInput();
        }

        // 3. Simpan ke tabel cv_submissions
        $cvSubmission = CvSubmission::create([
            'user_id' => Auth::id(), // [FIX Line 78] Menggunakan Facade Auth
            'original_filename' => $originalFilename,
            'stored_path' => $storedPath,
            'extracted_text' => $extractedText,
            'input_mode' => $inputMode,
        ]);

        // 4. Kirim ke AI lewat service
        try {
            $result = $this->cvAnalysisService->analyze($extractedText);
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['ai' => 'Terjadi kesalahan saat memproses CV di AI: ' . $e->getMessage()])
                ->withInput();
        }

        /**
         * Normalisasi hasil dari service
         */
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

            if (!empty($analysisRaw['feedback']) && is_array($analysisRaw['feedback'])) {
                $feedbackParts = [];
                foreach ($analysisRaw['feedback'] as $sectionTitle => $items) {
                    if (is_array($items) && count($items) > 0) {
                        $feedbackParts[] = strtoupper(str_replace('_', ' ', $sectionTitle)) . ':';
                        foreach ($items as $item) {
                            $feedbackParts[] = '- ' . $item;
                        }
                        $feedbackParts[] = ''; 
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
            'main_skills_json' => json_encode($result['main_skills'] ?? []),
            'division_recommendations_json' => json_encode($result['division_recommendations'] ?? []),
            'skill_gap_json' => json_encode($result['skill_gaps'] ?? []),
            'readiness_scores_json' => json_encode($result['readiness_scores'] ?? []),
            'feedback_text' => $result['feedback'] ?? null,
            'raw_ai_response' => json_encode($result),
        ]);

        return redirect()
            ->route('cv.result', $analysis->id)
            ->with('success', 'CV berhasil dianalisis.');
    }

    public function result($id)
    {
        $analysis = CvAnalysis::with('cvSubmission')->findOrFail($id);
        return view('cv.result', compact('analysis'));
    }

    protected function extractTextFromUploadedFile(UploadedFile $file): ?string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filePath = $file->getRealPath();

        try {
            $text = '';
            if ($extension === 'txt') {
                $text = file_get_contents($filePath) ?: '';
            } elseif ($extension === 'pdf') {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText() ?? '';
            } elseif ($extension === 'docx') {
                $phpWord = WordIOFactory::load($filePath, 'Word2007');
                $text = $this->extractTextFromPhpWord($phpWord);
            } elseif ($extension === 'doc') {
                $phpWord = WordIOFactory::load($filePath, 'MsDoc');
                $text = $this->extractTextFromPhpWord($phpWord);
            } else {
                return null;
            }

            $text = $this->normalizeCvText($text);

            if (trim($text) === '') {
                return null;
            }

            return $text;
        } catch (\Throwable $e) {
            // [FIX Line 242] Menggunakan Log facade yang sudah diimport
            Log::error('CV text extraction failed', [
                'extension' => $extension,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    protected function extractTextFromPhpWord($phpWord): string
    {
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
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

    protected function normalizeCvText(string $text): string
    {
        if (!mb_detect_encoding($text, 'UTF-8', true)) {
            $text = mb_convert_encoding($text, 'UTF-8');
        }
        $text = preg_replace('/[^\PC\s]/u', '', $text);
        $text = preg_replace("/\r\n|\r/", "\n", $text);
        $text = preg_replace("/[ \t]+/", ' ', $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim($text);
    }

    public function history()
    {
        $histories = CvAnalysis::with('cvSubmission')
            ->whereHas('cvSubmission', function ($q) {
                // [FIX Line 304] Ganti auth()->id() dengan Auth::id()
                $q->where('user_id', Auth::id());
            })
            ->latest()
            ->get();

        return view('cv.history', compact('histories'));
    }

    public function compareForm($id)
    {
        $current = CvAnalysis::with('cvSubmission')->findOrFail($id);

        $histories = CvAnalysis::with('cvSubmission')
            ->whereHas('cvSubmission', fn($q) =>
                // [FIX Line 318] Ganti auth()->id() dengan Auth::id()
                $q->where('user_id', Auth::id())
            )
            ->where('id', '!=', $id)
            ->latest()
            ->get();

        return view('cv.compare', compact('current', 'histories'));
    }

    public function compare(Request $request)
    {
        $request->validate([
            'cv_a' => 'required|exists:cv_analyses,id',
            'cv_b' => 'required|exists:cv_analyses,id',
        ]);

        $a = CvAnalysis::with('cvSubmission')->findOrFail($request->cv_a);
        $b = CvAnalysis::with('cvSubmission')->findOrFail($request->cv_b);

        return view('cv.compare-result', compact('a', 'b'));
    }

    public function destroyHistory(CvAnalysis $analysis)
    {
        // [FIX Line 344] Ganti auth()->id() dengan Auth::id()
        abort_if(
            $analysis->cvSubmission->user_id !== Auth::id(),
            403
        );

        $analysis->cvSubmission->delete();

        return redirect()
            ->route('cv.history')
            ->with('success', 'Riwayat CV berhasil dihapus.');
    }
}