<x-app-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="max-w-5xl mx-auto py-10 px-4">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold">CV Analysis History</h1>
                    <p class="text-sm text-slate-400">
                        Riwayat semua CV yang pernah kamu analisis.
                    </p>
                </div>

                <a href="{{ route('cv.create') }}"
                   class="rounded-full bg-sky-500 px-5 py-2 text-sm font-semibold text-white hover:bg-sky-400">
                    Analyze CV Baru
                </a>
            </div>

            <div class="space-y-4">
                @forelse ($histories as $h)
                    <div class="rounded-xl border border-slate-700 bg-slate-900/60 p-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold">
                                {{ $h->cvSubmission->original_filename }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $h->created_at->format('d M Y • H:i') }}
                                • Mode: {{ ucfirst($h->cvSubmission->input_mode) }}
                            </p>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="text-sm font-semibold text-sky-400">
                                {{ $h->resume_score }} / 100
                            </span>

                            <a href="{{ route('cv.result', $h->id) }}"
                               class="text-xs rounded-full border border-slate-600 px-3 py-1 hover:border-sky-400">
                                View Result
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">
                        Kamu belum pernah menganalisis CV.
                    </p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
