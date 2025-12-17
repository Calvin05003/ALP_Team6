<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">
        <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

            {{-- Header + Navigation --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <p class="text-xs font-semibold tracking-[0.25em] text-sky-400/80 uppercase">
                        CareerLens.AI
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-50">
                        Compare CV Versions
                    </h1>
                    <p class="mt-1 text-sm text-slate-400">
                        Bandingkan dua versi CV untuk melihat progres dan perbedaannya.
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

            {{-- Flash message (tidak dihapus) --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-500/40 bg-emerald-900/20
                            px-4 py-3 text-sm text-emerald-100 backdrop-blur">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Compare Form --}}
            <form method="POST"
                  action="{{ route('cv.compare') }}"
                  class="rounded-2xl border border-slate-700/70
                         bg-slate-900/70 p-6 space-y-5 shadow-lg shadow-slate-950/60">
                @csrf

                <input type="hidden" name="cv_a" value="{{ $current->id }}">

                {{-- Current CV --}}
                <div>
                    <label class="text-xs text-slate-400">
                        Current CV
                    </label>
                    <p class="mt-1 text-sm font-semibold text-sky-400">
                        {{ $current->cvSubmission->original_filename }}
                        <span class="text-xs text-slate-500">
                            (Score {{ $current->resume_score }})
                        </span>
                    </p>
                </div>

                {{-- Compare With --}}
                <div>
                    <label class="text-xs text-slate-400">
                        Compare With
                    </label>

                    <select name="cv_b"
                            required
                            class="w-full mt-1 rounded-xl bg-slate-950
                                   border border-slate-700 px-4 py-2
                                   text-sm text-slate-200 focus:border-emerald-400
                                   focus:ring-0">
                        @foreach($histories as $h)
                            <option value="{{ $h->id }}">
                                {{ $h->cvSubmission->original_filename }}
                                — Score {{ $h->resume_score }}
                                — {{ $h->created_at->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Action --}}
                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('cv.history') }}"
                       class="inline-flex items-center justify-center rounded-full
                              border border-slate-600 bg-slate-950/80
                              px-5 py-2 text-xs font-semibold text-slate-200
                              hover:border-sky-400 transition">
                        Back
                    </a>

                    <button
                        class="inline-flex items-center justify-center rounded-full
                               bg-emerald-500 px-6 py-2.5 text-sm font-semibold
                               text-white shadow-lg shadow-emerald-500/30
                               hover:bg-emerald-400 transition">
                        Compare
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
