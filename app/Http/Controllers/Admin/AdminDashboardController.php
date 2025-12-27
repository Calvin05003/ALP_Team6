<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CvAnalysis;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedMode = $request->analysis_mode;
        $selectedDivision = $request->division;

        $query = CvAnalysis::with(['cvSubmission.user'])
            ->whereHas('cvSubmission', function ($q) use ($selectedMode) {
                if ($selectedMode) {
                    $q->where('analysis_mode', $selectedMode);
                }
            });

        // 🔥 FILTER DIVISION DARI JSON
        if ($selectedDivision) {
            $query->where(function ($q) use ($selectedDivision) {
                $q->whereRaw(
                    "LOWER(division_recommendations_json) LIKE ?",
                    ['%' . strtolower($selectedDivision) . '%']
                );
            });
        }

        $analyses = $query
            ->orderByDesc('resume_score')
            ->get();

        return view('admin.dashboard', compact(
            'analyses',
            'selectedMode',
            'selectedDivision'
        ));
    }

}
