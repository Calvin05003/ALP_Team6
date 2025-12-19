<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">
        <div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

            {{-- Header + Navigation --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <p class="text-xs font-semibold tracking-[0.25em] text-sky-400/80 uppercase">
                        CareerLens.AI
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-50">
                        CV Comparison
                    </h1>
                    <p class="mt-1 text-sm text-slate-400">
                        Perbandingan dua versi CV berdasarkan skor analisis.
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('cv.history') }}"
                       class="inline-flex items-center justify-center rounded-full
                              border border-slate-600 px-4 py-2 text-xs font-semibold
                              text-slate-200 hover:border-sky-400 transition">
                        CV History
                    </a>

                    <a href="{{ route('cv.create') }}"
                       class="inline-flex items-center justify-center rounded-full
                              bg-sky-500 px-5 py-2.5 text-xs font-semibold text-white
                              shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition-all duration-150">
                        Analyze CV Baru
                        <span class="ml-1">↻</span>
                    </a>
                </div>
            </div>

            {{-- Compare Cards --}}
            <div class="grid md:grid-cols-2 gap-6 mb-8">

                {{-- CV A --}}
                <div class="rounded-2xl border border-sky-500/40 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60">
                    <h2 class="text-lg font-semibold text-sky-400 mb-1">
                        CV A
                    </h2>

                    <p class="text-xs text-slate-400 mb-4">
                        {{ $a->cvSubmission->original_filename }}
                        <span class="text-slate-500">
                            • {{ $a->created_at->format('d M Y') }}
                        </span>
                    </p>

                    <p class="text-4xl font-semibold text-sky-400">
                        {{ $a->resume_score }}
                        <span class="text-base text-slate-400">/ 100</span>
                    </p>
                </div>

                {{-- CV B --}}
                <div class="rounded-2xl border border-emerald-500/40 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60">
                    <h2 class="text-lg font-semibold text-emerald-400 mb-1">
                        CV B
                    </h2>

                    <p class="text-xs text-slate-400 mb-4">
                        {{ $b->cvSubmission->original_filename }}
                        <span class="text-slate-500">
                            • {{ $b->created_at->format('d M Y') }}
                        </span>
                    </p>

                    <p class="text-4xl font-semibold text-emerald-400">
                        {{ $b->resume_score }}
                        <span class="text-base text-slate-400">/ 100</span>
                    </p>
                </div>

            </div>

            {{-- Footer actions --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('cv.history') }}"
                   class="inline-flex items-center justify-center rounded-full
                          border border-slate-600 bg-slate-950/80
                          px-5 py-2.5 text-sm font-semibold text-slate-200
                          hover:border-sky-400 transition">
                    Back
                </a>

                <a href="{{ route('cv.create') }}"
                   class="inline-flex items-center justify-center rounded-full
                          bg-sky-500 px-6 py-2.5 text-sm font-semibold text-white
                          shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition-all duration-150">
                    Analyze CV Baru
                </a>
            </div>

        </div>
    </div>
</x-app-layout>