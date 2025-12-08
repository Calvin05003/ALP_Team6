<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">
        <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

            {{-- Header + CTA --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <p class="text-xs font-semibold tracking-[0.25em] text-sky-400/80 uppercase">
                        CareerLens.AI
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-50">
                        Hasil Analisis CV
                    </h1>
                    <p class="mt-1 text-sm text-slate-400">
                        Insight otomatis untuk membantu kamu memilih divisi yang paling cocok.
                    </p>
                </div>

                <a href="{{ route('cv.create') }}"
                   class="inline-flex items-center justify-center rounded-full bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition-all duration-150">
                    Analyze CV Lain
                    <span class="ml-2 text-xs">↻</span>
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-500/40 bg-emerald-900/20 px-4 py-3 text-sm text-emerald-100 backdrop-blur">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Meta info --}}
            <div class="mb-6 flex flex-wrap items-center gap-3 text-xs text-slate-400">
                <span class="rounded-full border border-slate-700/70 bg-slate-900/60 px-3 py-1">
                    Submission ID:
                    <span class="font-semibold text-slate-200">{{ $analysis->cv_submission_id }}</span>
                </span>
                <span class="rounded-full border border-slate-700/70 bg-slate-900/60 px-3 py-1">
                    Mode:
                    <span class="font-semibold text-slate-200">
                        {{ ucfirst($analysis->cvSubmission->input_mode ?? 'unknown') }}
                    </span>
                </span>
            </div>

            {{-- Normalisasi JSON sekali di sini --}}
            @php
                // Data utama dari kolom JSON spesifik
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

                // Decode raw_ai_response untuk field tambahan (ats_score, strengths, dll.)
                $raw = $analysis->raw_ai_response;
                if (is_string($raw)) {
                    $rawDecoded = json_decode($raw, true);
                    $rawDecoded = is_array($rawDecoded) ? $rawDecoded : [];
                } elseif (is_array($raw)) {
                    $rawDecoded = $raw;
                } else {
                    $rawDecoded = [];
                }

                $atsScore            = $rawDecoded['ats_score']           ?? null;
                $experienceLevel     = $rawDecoded['experience_level']    ?? null;
                $achievements        = $rawDecoded['achievements']        ?? [];
                $strengths           = $rawDecoded['strengths']           ?? [];
                $weaknesses          = $rawDecoded['weaknesses']          ?? [];
                $missingSections     = $rawDecoded['missing_sections']    ?? [];
                $suggestedImprovements = $rawDecoded['suggested_improvements'] ?? [];
                $grammarIssues       = $rawDecoded['grammar_issues']      ?? [];
                if (!is_array($achievements)) $achievements = [];
                if (!is_array($strengths)) $strengths = [];
                if (!is_array($weaknesses)) $weaknesses = [];
                if (!is_array($missingSections)) $missingSections = [];
                if (!is_array($suggestedImprovements)) $suggestedImprovements = [];
                if (!is_array($grammarIssues)) $grammarIssues = [];

                $grammarCritical = $grammarIssues['critical'] ?? 0;
                $grammarMinor    = $grammarIssues['minor']    ?? 0;
                $grammarSpelling = $grammarIssues['spelling'] ?? 0;
            @endphp

            {{-- Top summary cards --}}
            <div class="grid gap-6 md:grid-cols-[1.15fr,1.5fr] mb-8">
                {{-- Resume + ATS + Experience --}}
                <div class="relative overflow-hidden rounded-2xl border border-slate-700/70 bg-gradient-to-br from-slate-900 to-slate-900/40 p-5 shadow-xl shadow-slate-950/60">
                    <div class="absolute inset-0 pointer-events-none opacity-60"
                         style="background: radial-gradient(circle at 0 0, rgba(56,189,248,.18), transparent 55%);">
                    </div>

                    <div class="relative space-y-4">
                        <div>
                            <h2 class="text-sm font-semibold text-slate-300 mb-2">Resume Score</h2>
                            <div class="flex items-end gap-2">
                                <p class="text-4xl font-semibold text-sky-400">
                                    {{ $analysis->resume_score }}
                                </p>
                                <span class="mb-1 text-sm text-slate-400">/ 100</span>
                            </div>

                            {{-- progress bar kecil --}}
                            <div class="mt-4 h-1.5 w-full rounded-full bg-slate-800/80 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-sky-400 via-cyan-300 to-emerald-300"
                                     style="width: {{ max(0, min(100, (int) $analysis->resume_score)) }}%;">
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="rounded-xl border border-slate-700/80 bg-slate-950/70 px-3 py-2">
                                <p class="text-[11px] text-slate-400 mb-1">
                                    ATS Compatibility
                                </p>
                                <p class="text-sm font-semibold text-emerald-300">
                                    {{ $atsScore !== null ? $atsScore . ' / 100' : 'N/A' }}
                                </p>
                            </div>
                            <div class="rounded-xl border border-slate-700/80 bg-slate-950/70 px-3 py-2">
                                <p class="text-[11px] text-slate-400 mb-1">
                                    Experience Level
                                </p>
                                <p class="inline-flex items-center gap-1 text-sm font-semibold">
                                    @if ($experienceLevel)
                                        <span class="h-1.5 w-1.5 rounded-full bg-sky-400"></span>
                                        <span class="text-slate-100">{{ $experienceLevel }}</span>
                                    @else
                                        <span class="text-slate-500">Not estimated</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <p class="text-[11px] text-slate-500">
                            Skor dan level ini membantu kamu memahami seberapa siap CV kamu untuk pendaftaran kepanitiaan maupun sistem seleksi semi-ATS.
                        </p>
                    </div>
                </div>

                {{-- Main skills + Achievements --}}
                <div class="space-y-4">
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-5 shadow-lg shadow-slate-950/60">
                        <h2 class="text-sm font-semibold text-slate-200 mb-3">Main Skills Terdeteksi</h2>

                        <div class="flex flex-wrap gap-2">
                            @forelse($mainSkills as $skill)
                                @php
                                    $label = is_array($skill)
                                        ? ($skill['name'] ?? json_encode($skill))
                                        : $skill;
                                @endphp
                                <span class="inline-flex items-center rounded-full border border-slate-700 bg-slate-950/70 px-3 py-1 text-xs text-slate-200">
                                    <span class="mr-1 h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                    {{ $label }}
                                </span>
                            @empty
                                <p class="text-xs text-slate-500">
                                    Belum ada skill yang terdeteksi dari CV ini.
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-5 shadow-lg shadow-slate-950/60">
                        <h2 class="text-sm font-semibold text-slate-200 mb-3">Achievements (Pencapaian)</h2>
                        @if (count($achievements))
                            <ul class="list-disc pl-4 text-xs text-slate-300 space-y-1.5">
                                @foreach($achievements as $ach)
                                    <li>{{ $ach }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-xs text-slate-500">
                                Belum ada pencapaian spesifik yang terdeteksi. Coba tambahkan hasil yang terukur (contoh: “Meningkatkan jumlah peserta 30%”).
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Strengths & Weaknesses --}}
            <div class="grid gap-6 md:grid-cols-2 mb-8">
                <div class="rounded-2xl border border-emerald-500/40 bg-emerald-950/40 p-5 shadow-lg shadow-emerald-900/40">
                    <h2 class="text-sm font-semibold text-emerald-100 mb-3">
                        CV Strengths
                    </h2>
                    @if (count($strengths))
                        <ul class="list-disc pl-4 text-xs text-emerald-50 space-y-1.5">
                            @foreach($strengths as $s)
                                <li>{{ $s }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs text-emerald-200/70">
                            Belum ada strength spesifik. Biasanya berisi hal-hal yang sudah kamu lakukan dengan baik, seperti struktur rapi, pengalaman relevan, atau penggunaan bahasa yang jelas.
                        </p>
                    @endif
                </div>

                <div class="rounded-2xl border border-rose-500/40 bg-rose-950/40 p-5 shadow-lg shadow-rose-900/40">
                    <h2 class="text-sm font-semibold text-rose-100 mb-3">
                        CV Weaknesses
                    </h2>
                    @if (count($weaknesses))
                        <ul class="list-disc pl-4 text-xs text-rose-50 space-y-1.5">
                            @foreach($weaknesses as $w)
                                <li>{{ $w }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-xs text-rose-200/80">
                            Tidak ada weakness spesifik yang terdeteksi, atau model belum memetakan secara eksplisit.
                        </p>
                    @endif
                </div>
            </div>

            {{-- Division recommendations --}}
            <div class="mb-6 rounded-2xl border border-slate-700/70 bg-slate-900/70 p-5 shadow-lg shadow-slate-950/60">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-slate-200">
                        Rekomendasi Divisi
                    </h2>
                    <span class="rounded-full border border-slate-700 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-400">
                        Match, Keyword &amp; Alasan
                    </span>
                </div>

                @forelse($divisionRecs as $rec)
                    @php
                        $keywordMatch = $rec['keyword_match'] ?? null;
                    @endphp
                    <div class="mb-4 last:mb-0 rounded-xl border border-slate-800/80 bg-slate-950/60 px-4 py-3 space-y-1.5">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-slate-100">
                                {{ $rec['division_name'] ?? '-' }}
                            </p>
                            @if ($keywordMatch !== null)
                                <span class="text-[11px] text-sky-300 font-medium">
                                    Keyword match: {{ $keywordMatch }}%
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400">
                            {{ $rec['reason'] ?? '' }}
                        </p>
                    </div>
                @empty
                    <p class="text-xs text-slate-500">
                        Belum ada rekomendasi divisi yang bisa ditampilkan.
                    </p>
                @endforelse
            </div>

            {{-- Readiness + Skill gaps + Missing sections / Grammar --}}
            <div class="grid gap-6 md:grid-cols-3 mb-8">
                {{-- Readiness per division --}}
                <div class="md:col-span-1 rounded-2xl border border-slate-700/70 bg-slate-900/70 p-5 shadow-lg shadow-slate-950/60">
                    <h2 class="text-sm font-semibold text-slate-200 mb-3">
                        Skor Kesiapan per Divisi
                    </h2>
                    <ul class="space-y-2">
                        @forelse($readinessScores as $r)
                            <li class="flex items-center justify-between rounded-xl bg-slate-950/60 px-3 py-2">
                                <span class="text-xs text-slate-300">
                                    {{ $r['division_name'] ?? '-' }}
                                </span>
                                <span class="text-xs font-semibold text-sky-300">
                                    {{ $r['score'] ?? 0 }} / 100
                                </span>
                            </li>
                        @empty
                            <li class="text-xs text-slate-500">
                                Belum ada skor kesiapan yang tersedia.
                            </li>
                        @endforelse
                    </ul>
                </div>

                {{-- Skill gaps --}}
                <div class="md:col-span-1 rounded-2xl border border-slate-700/70 bg-slate-900/70 p-5 shadow-lg shadow-slate-950/60">
                    <h2 class="text-sm font-semibold text-slate-200 mb-3">
                        Skill Gaps (Per Divisi)
                    </h2>

                    @forelse($skillGaps as $gap)
                        <div class="mb-4 last:mb-0 rounded-xl bg-slate-950/60 px-3 py-3">
                            <p class="text-xs font-semibold text-slate-100">
                                {{ $gap['division_name'] ?? '-' }}
                            </p>
                            <ul class="mt-1 list-disc pl-4 text-[11px] text-slate-400">
                                @foreach(($gap['missing_skills'] ?? []) as $ms)
                                    <li>{{ $ms }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500">
                            Tidak ada skill gap yang terdeteksi atau belum dianalisis.
                        </p>
                    @endforelse
                </div>

                {{-- Missing sections + Grammar --}}
                <div class="md:col-span-1 space-y-4">
                    <div class="rounded-2xl border border-amber-500/40 bg-amber-950/40 p-4 shadow-lg shadow-amber-900/40">
                        <h2 class="text-sm font-semibold text-amber-100 mb-2">
                            Missing / Lemah di Bagian Ini
                        </h2>
                        @if (count($missingSections))
                            <ul class="list-disc pl-4 text-[11px] text-amber-50 space-y-1.5">
                                @foreach($missingSections as $m)
                                    <li>{{ $m }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-[11px] text-amber-100/80">
                                Tidak ada bagian yang jelas-jelas hilang, atau model tidak mendeteksi kekurangan struktur besar.
                            </p>
                        @endif
                    </div>

                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/80 p-4 shadow-lg shadow-slate-950/60">
                        <h2 class="text-sm font-semibold text-slate-200 mb-2">
                            Grammar &amp; Spelling Issues
                        </h2>
                        <dl class="grid grid-cols-3 gap-2 text-[11px] text-slate-200">
                            <div class="rounded-lg bg-slate-950/70 px-2.5 py-2 text-center">
                                <dt class="text-slate-400 mb-0.5">Critical</dt>
                                <dd class="font-semibold">{{ $grammarCritical }}</dd>
                            </div>
                            <div class="rounded-lg bg-slate-950/70 px-2.5 py-2 text-center">
                                <dt class="text-slate-400 mb-0.5">Minor</dt>
                                <dd class="font-semibold">{{ $grammarMinor }}</dd>
                            </div>
                            <div class="rounded-lg bg-slate-950/70 px-2.5 py-2 text-center">
                                <dt class="text-slate-400 mb-0.5">Spelling</dt>
                                <dd class="font-semibold">{{ $grammarSpelling }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- Feedback + Actionable suggestions --}}
            <div class="rounded-2xl border border-slate-700/70 bg-slate-900/80 p-6 shadow-xl shadow-slate-950/70 mb-6">
                <h2 class="text-sm font-semibold text-slate-200 mb-3">
                    Feedback Detail untuk CV Kamu
                </h2>
                <p class="whitespace-pre-line text-sm leading-relaxed text-slate-200/90 mb-4">
                    {{ $analysis->feedback_text ?? 'Belum ada feedback tertulis.' }}
                </p>

                <h3 class="text-xs font-semibold text-slate-300 mb-2">
                    Suggested Improvements (Langkah Next untuk Upgrade CV)
                </h3>
                @if (count($suggestedImprovements))
                    <ul class="list-disc pl-4 text-xs text-slate-300 space-y-1.5">
                        @foreach($suggestedImprovements as $si)
                            <li>{{ $si }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-[11px] text-slate-500">
                        Model belum memberikan action steps spesifik, tapi kamu bisa mulai dengan: menambah pencapaian yang terukur, memperjelas deskripsi role, dan merapikan struktur section.
                    </p>
                @endif
            </div>

            <div class="flex justify-end">
                <a href="{{ route('cv.create') }}"
                   class="inline-flex items-center justify-center rounded-full border border-slate-600 bg-slate-950/80 px-5 py-2.5 text-sm font-semibold text-slate-100 hover:border-sky-400 hover:text-sky-100 hover:bg-slate-900 transition-all duration-150">
                    Kembali ke Upload / Input CV
                </a>
            </div>
        </div>
    </div>
</x-app-layout>