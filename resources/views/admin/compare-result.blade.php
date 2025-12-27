<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">

        {{-- Background Glow --}}
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-sky-600/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-600/10 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-10">
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">
                    Perbandingan CV
                </h1>
                <p class="text-slate-400 max-w-2xl">
                    Analisis dua versi CV untuk melihat perbedaan skor, divisi rekomendasi, dan kekuatan utama.
                </p>
            </div>

            {{-- Comparison Grid --}}
            <div class="grid lg:grid-cols-2 gap-8 mb-10">

                {{-- CV A --}}
                <div class="rounded-3xl bg-slate-900/60 backdrop-blur-xl border border-slate-700/60 p-6 shadow-xl">
                    <h3 class="text-xl font-semibold text-sky-400 mb-4">CV A</h3>

                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="text-slate-400">Nama File</span>
                            <p class="font-medium text-slate-200">{{ $cvA->cvSubmission->original_filename }}</p>
                        </div>

                        <div>
                            <span class="text-slate-400">User</span>
                            <p class="text-slate-200">{{ $cvA->cvSubmission->user->name }}</p>
                        </div>

                        <div>
                            <span class="text-slate-400">Resume Score</span>
                            <p class="text-2xl font-bold text-emerald-400">{{ $cvA->resume_score }}/100</p>
                        </div>

                        <div>
                            <span class="text-slate-400">Divisi Rekomendasi</span>
                            <p class="font-semibold text-slate-100">{{ $cvA->recommended_division ?? '-' }}</p>
                        </div>

                        <div>
                            <span class="text-slate-400">Kekuatan Utama</span>
                            <ul class="list-disc list-inside text-slate-300 mt-1">
                                @foreach(($cvA->strengths ?? []) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- CV B --}}
                <div class="rounded-3xl bg-slate-900/60 backdrop-blur-xl border border-slate-700/60 p-6 shadow-xl">
                    <h3 class="text-xl font-semibold text-emerald-400 mb-4">CV B</h3>

                    <div class="space-y-4 text-sm">
                        <div>
                            <span class="text-slate-400">Nama File</span>
                            <p class="font-medium text-slate-200">{{ $cvB->cvSubmission->original_filename }}</p>
                        </div>

                        <div>
                            <span class="text-slate-400">User</span>
                            <p class="text-slate-200">{{ $cvB->cvSubmission->user->name }}</p>
                        </div>

                        <div>
                            <span class="text-slate-400">Resume Score</span>
                            <p class="text-2xl font-bold text-sky-400">{{ $cvB->resume_score }}/100</p>
                        </div>

                        <div>
                            <span class="text-slate-400">Divisi Rekomendasi</span>
                            <p class="font-semibold text-slate-100">{{ $cvB->recommended_division ?? '-' }}</p>
                        </div>

                        <div>
                            <span class="text-slate-400">Kekuatan Utama</span>
                            <ul class="list-disc list-inside text-slate-300 mt-1">
                                @foreach(($cvB->strengths ?? []) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            {{-- AI Comparison Summary --}}
            <div class="rounded-3xl bg-gradient-to-br from-slate-900/80 to-slate-900/40 backdrop-blur-xl border border-slate-700/60 p-8 shadow-2xl">
                <h3 class="text-2xl font-bold text-white mb-4">Ringkasan Perbandingan AI</h3>
                <p class="text-slate-300 leading-relaxed mb-6">
                    {{ $comparisonResult->summary ?? 'Ringkasan belum tersedia.' }}
                </p>

                <div class="grid md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <h4 class="font-semibold text-emerald-400 mb-2">Peningkatan pada CV B</h4>
                        <ul class="list-disc list-inside text-slate-300">
                            @foreach(($comparisonResult->improvements ?? []) as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h4 class="font-semibold text-rose-400 mb-2">Area yang Masih Perlu Ditingkatkan</h4>
                        <ul class="list-disc list-inside text-slate-300">
                            @foreach(($comparisonResult->weaknesses ?? []) as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-10 flex flex-wrap gap-4">
                <a href="{{ route('cv.history') }}"
                   class="inline-flex items-center justify-center rounded-full border border-slate-600 px-6 py-2 text-sm font-semibold text-slate-200 hover:border-sky-400 hover:text-white transition-all">
                    Kembali ke Riwayat
                </a>

                <a href="{{ route('cv.create') }}"
                   class="inline-flex items-center justify-center rounded-full bg-sky-500 px-6 py-2 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 hover:bg-sky-400 transition-all">
                    Analisis CV Baru
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
