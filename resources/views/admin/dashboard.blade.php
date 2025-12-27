<x-app-layout>
<div class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950
            text-slate-100 p-6">

    <h1 class="text-2xl font-bold mb-6">📊 Admin CV Ranking</h1>

    {{-- FILTER --}}
    <form method="GET" class="flex flex-wrap gap-4 mb-8 items-center">

        {{-- MODE --}}
        <select name="analysis_mode"
                class="bg-slate-800 border border-slate-700 rounded px-4 py-2">
            <option value="">All Types</option>
            <option value="professional" {{ ($selectedMode ?? '')=='professional'?'selected':'' }}>
                Professional
            </option>
            <option value="committee" {{ ($selectedMode ?? '')=='committee'?'selected':'' }}>
                Committee
            </option>
        </select>

        {{-- DIVISION --}}
        <input type="text"
               name="division"
               placeholder="Filter division (ex: IT, Design)"
               value="{{ $selectedDivision ?? '' }}"
               class="bg-slate-800 border border-slate-700 rounded px-4 py-2">

        <button class="bg-sky-600 hover:bg-sky-500 px-5 py-2 rounded font-semibold transition">
            Filter
        </button>

        <a href="{{ route('admin.dashboard') }}"
           class="text-xs text-slate-400 hover:text-white ml-auto">
            Reset
        </a>
    </form>

    {{-- LIST --}}
    <div class="space-y-4">
        @forelse($analyses as $rank => $analysis)
            @php
                // Decode division JSON aman
                $divisions = json_decode($analysis->division_recommendations_json, true);
                if (!is_array($divisions)) $divisions = [];
            @endphp

            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4
                        bg-slate-900/70 border border-slate-700
                        rounded-xl p-4 hover:border-sky-500 transition">

                {{-- LEFT --}}
                <div>
                    <div class="font-semibold text-lg">
                        #{{ $rank+1 }} — {{ $analysis->cvSubmission->user->name ?? 'Unknown User' }}
                    </div>

                    <div class="text-xs text-slate-400 mt-1">
                        Type:
                        <span class="text-sky-300 font-medium">
                            {{ ucfirst($analysis->cvSubmission->analysis_mode ?? '-') }}
                        </span>
                    </div>

                    {{-- DIVISION BADGES --}}
                    <div class="flex flex-wrap gap-2 mt-2">
                        @forelse($divisions as $div)
                            @php
                                $divName = $div['division_name'] ?? '';
                                $isSelected = $selectedDivision &&
                                              str_contains(strtolower($divName), strtolower($selectedDivision));
                            @endphp

                            <span class="text-xs px-2 py-1 rounded
                                {{ $isSelected ? 'bg-emerald-600' : 'bg-slate-800' }}">
                                {{ $divName ?: '-' }}
                            </span>
                        @empty
                            <span class="text-xs text-slate-500">No division data</span>
                        @endforelse
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="text-right">
                    <div class="text-xs text-slate-400">Resume Score</div>
                    <div class="text-3xl font-bold text-emerald-400">
                        {{ number_format($analysis->resume_score ?? 0, 1) }}
                    </div>

                    <a href="{{ route('admin.cv.result', $analysis->id) }}"
                       class="text-xs text-sky-400 hover:underline mt-1 inline-block">
                        View Analysis →
                    </a>
                </div>
            </div>
        @empty
            <p class="text-slate-400">Belum ada CV dikirim ke admin</p>
        @endforelse
    </div>
</div>
</x-app-layout>
