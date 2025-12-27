<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">
        <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
                <div>
                    <p class="text-xs font-semibold tracking-[0.25em] text-sky-400/80 uppercase">
                        CareerLens.AI
                    </p>
                    <h1 class="mt-2 text-3xl font-semibold text-slate-50">
                        CV Analysis History
                    </h1>
                    <p class="mt-1 text-sm text-slate-400">
                        Riwayat seluruh CV yang pernah kamu analisis.
                    </p>
                </div>

                <a href="{{ route('cv.create') }}"
                   class="inline-flex items-center justify-center rounded-full bg-sky-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition-all">
                    Analyze CV Baru
                </a>
            </div>

            {{-- History list --}}
            <div class="space-y-4">
                @forelse($histories as $index => $h)
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-5 shadow-lg shadow-slate-950/60">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                            <div>
                                <p class="text-sm font-semibold text-slate-100">
                                    {{ $h->cvSubmission->original_filename }}
                                </p>
                                <p class="text-xs text-slate-400 mt-1">
                                    {{ $h->created_at->format('d M Y • H:i') }}
                                    • Mode: {{ ucfirst($h->cvSubmission->input_mode) }}
                                    • Version #{{ $histories->count() - $index }}
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="text-lg font-semibold text-sky-400">
                                    {{ $h->resume_score }}/100
                                </span>

                                {{-- Tombol View --}}
                                <a href="{{ route('cv.result', $h->id) }}"
                                   class="rounded-full border border-slate-600 px-4 py-1.5 text-xs font-semibold hover:border-sky-400 transition">
                                    View
                                </a>

                                {{-- Tombol Delete --}}
                                <form method="POST"
                                      action="{{ route('cv.history.delete', $h->id) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus versi CV ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-full border border-rose-500/40 px-3 py-1.5 text-xs font-semibold text-rose-400 hover:bg-rose-500/10 transition">
                                        Delete
                                    </button>
                                </form>

                                {{-- Tombol Push to Admin --}}
                                <form method="POST" action="{{ route('cv.push-to-admin', $h->cvSubmission->id) }}">
                                    @csrf
                                    <button type="submit"
                                        class="rounded-full px-3 py-1.5 text-xs font-semibold
                                               {{ $h->cvSubmission->is_submitted_to_admin ? 'bg-slate-600 text-slate-400 cursor-not-allowed' : 'bg-emerald-500 text-white hover:bg-emerald-400' }}"
                                        {{ $h->cvSubmission->is_submitted_to_admin ? 'disabled' : '' }}>
                                        Push to Admin
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 text-center text-slate-400">
                        Belum ada riwayat analisis CV.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
