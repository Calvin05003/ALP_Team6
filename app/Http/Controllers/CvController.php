<?php

namespace App\Http\Controllers;

use App\Models\CvAnalysis;
use App\Models\CvSubmission;
use App\Services\CvAnalysisService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

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

            $extractedText = $this->extractTextFromUploadedFile($file);
        } else {
            // input manual
            $originalFilename = 'manual-input-' . now()->timestamp . '.txt';
            $storedPath = null;
            $extractedText = $request->input('cv_text');
        }

        // Safety fallback
        if (empty($extractedText)) {
            return back()
                ->withErrors(['cv_file' => 'Gagal membaca isi CV. Pastikan format file benar atau isi teks secara manual.'])
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
            return back()
                ->withErrors(['ai' => 'Terjadi kesalahan saat memproses CV di AI: ' . $e->getMessage()])
                ->withInput();
        }

        /**
         * Normalisasi hasil dari service supaya punya struktur yang konsisten:
         *
         * Kita HARUS mengisi key:
         * - resume_score            → int
         * - main_skills             → array of string
         * - division_recommendations→ array of { division_name, reason }
         * - readiness_scores        → array of { division_name, score }
         * - skill_gaps              → array of { division_name, missing_skills[] }
         * - feedback                → string (boleh gabungan beberapa poin)
         */

        // Kalau service sudah mengembalikan langsung struktur ini, tinggal pakai.
// Tapi kalau dia pakai wrapper seperti ["analysis" => [...]], kita tangani.
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
     * Di sini kamu bisa integrasi library PDF/DOCX sesuai kebutuhan.
     */
    protected function extractTextFromUploadedFile(UploadedFile $file): ?string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        // Contoh sederhana:
        if ($extension === 'txt') {
            return file_get_contents($file->getRealPath());
        }

        // TODO:
        // - Untuk PDF: pakai library seperti smalot/pdfparser atau spatie/pdf-to-text
        // - Untuk DOC/DOCX: pakai PhpOffice\PhpWord atau library serupa
        // Sementara, return null dulu kalau belum diimplementasi
        return null;
    }
}