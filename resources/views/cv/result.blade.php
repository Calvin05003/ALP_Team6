<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Hasil Analisis CV</h1>

        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6">
            <p class="text-sm text-gray-600">
                Submission ID: {{ $analysis->cv_submission_id }} |
                Mode: {{ $analysis->cvSubmission->input_mode ?? '-' }}
            </p>
        </div>

        {{-- Normalisasi semua JSON ke array di satu tempat --}}
        @php
            $mainSkills = $analysis->main_skills_json;
            if (is_string($mainSkills)) {
                $decoded = json_decode($mainSkills, true);
                $mainSkills = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($mainSkills)) {
                $mainSkills = [];
            }

            $divisionRecs = $analysis->division_recommendations_json;
            if (is_string($divisionRecs)) {
                $decoded = json_decode($divisionRecs, true);
                $divisionRecs = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($divisionRecs)) {
                $divisionRecs = [];
            }

            $readinessScores = $analysis->readiness_scores_json;
            if (is_string($readinessScores)) {
                $decoded = json_decode($readinessScores, true);
                $readinessScores = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($readinessScores)) {
                $readinessScores = [];
            }

            $skillGaps = $analysis->skill_gap_json;
            if (is_string($skillGaps)) {
                $decoded = json_decode($skillGaps, true);
                $skillGaps = is_array($decoded) ? $decoded : [];
            } elseif (!is_array($skillGaps)) {
                $skillGaps = [];
            }
        @endphp

        <div class="grid gap-6 md:grid-cols-2">
            <div class="border rounded p-4">
                <h2 class="font-semibold mb-2">Resume Score</h2>
                <p class="text-3xl font-bold">{{ $analysis->resume_score }} / 100</p>
            </div>

            <div class="border rounded p-4">
                <h2 class="font-semibold mb-2">Main Skills</h2>
                <ul class="list-disc pl-5">
                    @forelse($mainSkills as $skill)
                        <li>{{ is_array($skill) ? ($skill['name'] ?? json_encode($skill)) : $skill }}</li>
                    @empty
                        <li class="text-sm text-gray-500">Belum ada skill yang terdeteksi.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="mt-6 border rounded p-4">
            <h2 class="font-semibold mb-2">Division Recommendations</h2>

            @forelse($divisionRecs as $rec)
                <div class="mb-3">
                    <p class="font-semibold">{{ $rec['division_name'] ?? '-' }}</p>
                    <p class="text-sm text-gray-700">{{ $rec['reason'] ?? '' }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada rekomendasi divisi.</p>
            @endforelse
        </div>

        <div class="mt-6 border rounded p-4">
            <h2 class="font-semibold mb-2">Readiness Scores per Division</h2>
            <ul class="list-disc pl-5">
                @forelse($readinessScores as $r)
                    <li>
                        {{ $r['division_name'] ?? '-' }}:
                        <strong>{{ $r['score'] ?? 0 }}</strong> / 100
                    </li>
                @empty
                    <li class="text-sm text-gray-500">Belum ada skor kesiapan.</li>
                @endforelse
            </ul>
        </div>

        <div class="mt-6 border rounded p-4">
            <h2 class="font-semibold mb-2">Skill Gaps</h2>
            @forelse($skillGaps as $gap)
                <div class="mb-3">
                    <p class="font-semibold">{{ $gap['division_name'] ?? '-' }}</p>
                    <ul class="list-disc pl-5 text-sm">
                        @foreach(($gap['missing_skills'] ?? []) as $ms)
                            <li>{{ $ms }}</li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <p class="text-sm text-gray-500">Tidak ada skill gap yang terdeteksi atau belum dianalisis.</p>
            @endforelse
        </div>

        <div class="mt-6 border rounded p-4">
            <h2 class="font-semibold mb-2">Feedback</h2>
            <p class="whitespace-pre-line text-sm text-gray-800">
                {{ $analysis->feedback_text ?? 'Belum ada feedback tertulis.' }}
            </p>
        </div>
    </div>
</x-app-layout>