<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CvAnalysis;

class AdminController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya admin yang bisa mengakses
        $this->middleware(['auth', 'role:admin']);
    }

    // Form untuk memilih CV yang ingin dibandingkan
    public function compareForm()
    {
        $analyses = CvAnalysis::with('cvSubmission.user')->get();
        return view('admin.compare', compact('analyses'));
    }

    // Hasil perbandingan CV
    public function compareResult(Request $request)
    {
        $request->validate([
            'cv_a' => 'required|different:cv_b|exists:cv_analyses,id',
            'cv_b' => 'required|exists:cv_analyses,id',
        ]);

        $cvA = CvAnalysis::with('cvSubmission.user')->findOrFail($request->cv_a);
        $cvB = CvAnalysis::with('cvSubmission.user')->findOrFail($request->cv_b);

        return view('admin.compare-result', compact('cvA', 'cvB'));
    }
}
