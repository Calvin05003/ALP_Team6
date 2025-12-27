<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CvAnalysis;
use App\Models\CvSubmission;
use Illuminate\Support\Str;
use App\Services\CvAnalysisService;
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
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx,txt|max:10240',
            'language' => 'required|string|in:id,en',
            'analysis_type' => 'required|string|in:kepanitiaan,professional',
        ]);

        $file = $request->file('cv');
        $originalFilename = $file->getClientOriginalName();
        $storedPath = $file->storeAs('cvs', Str::uuid() . '.' . $file->getClientOriginalExtension(), 'public');

        // Extract teks dari CV
        $extractedText = $this->extractTextFromUploadedFile($file);
        if (!$extractedText) {
            return back()->withErrors(['cv' => 'Gagal membaca isi CV. Pastikan file benar.'])->withInput();
        }

        // Mapping analysis_type ke enum
        $analysisMode = $request->analysis_type === 'kepanitiaan' ? 'committee' : 'professional';

        // Simpan submission
        $submission = CvSubmission::create([
            'user_id' => auth()->id(),
            'stored_path' => $storedPath,
            'original_filename' => $originalFilename,
            'input_mode' => 'file',
            'language' => $request->language,
            'analysis_mode' => $analysisMode,
        ]);

        // Analisis CV lewat service
        try {
            $result = $this->cvAnalysisService->analyze($extractedText);
        } catch (\Throwable $e) {
            return back()->withErrors(['ai' => 'Terjadi kesalahan saat analisis AI: '.$e->getMessage()])->withInput();
        }

        // Normalisasi hasil service supaya konsisten
        $normalized = [
            'resume_score' => $result['resume_score'] ?? 0,
            'main_skills_json' => json_encode($result['main_skills'] ?? []),
            'division_recommendations_json' => json_encode($result['division_recommendations'] ?? []),
            'readiness_scores_json' => json_encode($result['readiness_scores'] ?? []),
            'skill_gap_json' => json_encode($result['skill_gaps'] ?? []),
            'feedback_text' => $result['feedback_text'] ?? null,
            'raw_ai_response' => json_encode($result),
        ];

        $analysis = CvAnalysis::create(array_merge(['cv_submission_id' => $submission->id], $normalized));

        return redirect()->route('cv.result', $analysis->id)
                         ->with('success', 'CV berhasil dianalisis!');
    }

    public function result(CvAnalysis $analysis)
    {
        if (!$analysis->cvSubmission) abort(404, 'Submission not found.');
        if (auth()->user()->role !== 'admin' && $analysis->cvSubmission->user_id !== auth()->id()) abort(403);

        return view('cv.result', compact('analysis'));
    }

    public function history()
    {
        $histories = CvAnalysis::with('cvSubmission')
                        ->whereHas('cvSubmission', fn($q) => $q->where('user_id', auth()->id()))
                        ->latest()
                        ->get();

        // Cek apakah sudah ada CV yang dikirim ke admin
        $alreadySubmitted = $histories->where('cvSubmission.is_submitted_to_admin', true)->count() > 0;

        return view('cv.history', compact('histories', 'alreadySubmitted'));
    }

    public function destroyHistory(CvAnalysis $analysis)
    {
        if (!$analysis->cvSubmission || (auth()->user()->role !== 'admin' && $analysis->cvSubmission->user_id !== auth()->id())) {
            abort(403);
        }

        Storage::disk('public')->delete($analysis->cvSubmission->stored_path);
        $analysis->cvSubmission->delete(); // Cascade delete analysis
        $analysis->delete();

        return redirect()->route('cv.history')->with('success', 'CV berhasil dihapus.');
    }

    // Compare hanya untuk admin
    public function compareForm(CvAnalysis $analysis)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $allAnalyses = CvAnalysis::where('id', '!=', $analysis->id)->get();
        return view('cv.compare', compact('analysis', 'allAnalyses'));
    }

    public function compare(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'analysis_1' => 'required|exists:cv_analyses,id',
            'analysis_2' => 'required|exists:cv_analyses,id|different:analysis_1',
        ]);

        $analysis1 = CvAnalysis::findOrFail($request->analysis_1);
        $analysis2 = CvAnalysis::findOrFail($request->analysis_2);

        return view('cv.compare-result', compact('analysis1', 'analysis2'));
    }

    public function submitToAdmin(CvSubmission $submission)
    {
        // Pastikan CV milik user
        if ($submission->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Reset semua CV user sebelumnya yang dikirim
        CvSubmission::where('user_id', auth()->id())
                    ->update(['is_submitted_to_admin' => false]);

        // Set CV yang dipilih menjadi dikirim
        $submission->is_submitted_to_admin = true;
        $submission->save();

        return redirect()->route('cv.history')
                        ->with('success', 'CV berhasil dipush ke admin.');
    }





    // --- Helper ekstraksi file ---
    protected function extractTextFromUploadedFile(UploadedFile $file): ?string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        try {
            $text = '';
            if ($extension === 'txt') {
                $text = file_get_contents($path) ?: '';
            } elseif ($extension === 'pdf') {
                $parser = new PdfParser();
                $pdf = $parser->parseFile($path);
                $text = $pdf->getText() ?? '';
            } elseif ($extension === 'docx') {
                $phpWord = WordIOFactory::load($path, 'Word2007');
                $text = $this->extractTextFromPhpWord($phpWord);
            } elseif ($extension === 'doc') {
                $phpWord = WordIOFactory::load($path, 'MsDoc');
                $text = $this->extractTextFromPhpWord($phpWord);
            } else {
                return null;
            }

            return $this->normalizeCvText($text);
        } catch (\Throwable $e) {
            \Log::error('CV extraction failed', ['file'=>$file->getClientOriginalName(),'error'=>$e->getMessage()]);
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
                        if (method_exists($child, 'getText')) $text .= $child->getText() . "\n";
                    }
                }
            }
        }
        return $text;
    }

    protected function normalizeCvText(string $text): string
    {
        if (!mb_detect_encoding($text, 'UTF-8', true)) $text = mb_convert_encoding($text, 'UTF-8');
        $text = preg_replace('/[^\PC\s]/u', '', $text);
        $text = preg_replace("/\r\n|\r/", "\n", $text);
        $text = preg_replace("/[ \t]+/", ' ', $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);
        return trim($text);
    }
}
