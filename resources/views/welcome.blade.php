<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CareerLens.AI - Smart CV Analysis</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            {{-- Include your Tailwind CSS here --}}
        @endif
    </head>
    <body class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">
        
        {{-- Header Navigation --}}
        @if (Route::has('login'))
            <header class="w-full max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <nav class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-sky-400 to-cyan-300 flex items-center justify-center">
                            <svg class="h-5 w-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-lg font-semibold text-slate-100">CareerLens<span class="text-sky-400">.AI</span></span>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="flex flex-wrap justify-center gap-4">
                                @auth
                                    @if(auth()->user()->role === 'user')
                                        <a href="{{ route('cv.create') }}"
                                        class="inline-flex items-center justify-center rounded-full bg-sky-500 px-8 py-3 text-base font-semibold text-white shadow-lg hover:bg-sky-400 transition-all">
                                            Upload CV Sekarang
                                        </a>

                                        <a href="{{ route('cv.history') }}"
                                        class="inline-flex items-center justify-center rounded-full border border-slate-600 bg-slate-900/50 px-8 py-3 text-base font-semibold text-slate-200 hover:border-sky-400 transition-all">
                                            Lihat History
                                        </a>
                                    @elseif(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}"
                                        class="inline-flex items-center justify-center rounded-full bg-emerald-500 px-8 py-3 text-base font-semibold text-white shadow-lg hover:bg-emerald-400 transition-all">
                                            Masuk Dashboard Admin
                                        </a>
                                    @endif
                                @else
                                    {{-- GUEST --}}
                                    <a href="{{ route('register') }}"
                                    class="inline-flex items-center justify-center rounded-full bg-sky-500 px-8 py-3 text-base font-semibold text-white shadow-lg hover:bg-sky-400 transition-all">
                                        Daftar
                                    </a>

                                    <a href="{{ route('login') }}"
                                    class="inline-flex items-center justify-center rounded-full border border-slate-600 bg-slate-900/50 px-8 py-3 text-base font-semibold text-slate-200 hover:border-sky-400 transition-all">
                                        Login
                                    </a>
                                @endauth
                            </div>
                    </div>
                </nav>
            </header>
        @endif

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            
            {{-- Hero Section --}}
            <div class="text-center mb-20">
                <div class="relative overflow-hidden rounded-3xl border border-slate-700/70 bg-gradient-to-br from-slate-900 to-slate-900/40 p-16 shadow-2xl shadow-slate-950/60 mb-12">
                    <div class="absolute inset-0 pointer-events-none opacity-40"
                         style="background: radial-gradient(circle at 50% 0, rgba(56,189,248,.25), transparent 70%);">
                    </div>
                    
                    <div class="relative">
                        {{-- Logo/Icon --}}
                        <div class="flex justify-center mb-6">
                            <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-sky-400 via-cyan-300 to-emerald-300 flex items-center justify-center shadow-2xl shadow-sky-500/50">
                                <svg class="h-11 w-11 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>

                        <h1 class="text-5xl font-bold text-slate-50 mb-4">
                            Welcome to <span class="bg-gradient-to-r from-sky-400 to-cyan-300 bg-clip-text text-transparent">CareerLens.AI</span>
                        </h1>
                        <p class="text-lg text-slate-300 max-w-3xl mx-auto mb-8">
                            Analisis CV cerdas berbasis AI untuk membantu kamu menemukan divisi yang paling cocok dengan skill dan pengalamanmu.
                        </p>

                        <div class="flex flex-wrap justify-center gap-4">
                           @auth
                                @if(auth()->user()->role === 'user')
                                    <a href="{{ route('cv.create') }}"
                                    class="inline-flex items-center justify-center rounded-full bg-sky-500 px-8 py-3 text-base font-semibold text-white shadow-lg hover:bg-sky-400 transition-all">
                                        Upload CV Sekarang
                                    </a>

                                    <a href="{{ route('cv.history') }}"
                                    class="inline-flex items-center justify-center rounded-full border border-slate-600 bg-slate-900/50 px-8 py-3 text-base font-semibold text-slate-200 hover:border-sky-400 transition-all">
                                        Lihat History
                                    </a>
                                @else
                                    <a href="{{ route('admin.dashboard') }}"
                                    class="inline-flex items-center justify-center rounded-full bg-emerald-500 px-8 py-3 text-base font-semibold text-white shadow-lg hover:bg-emerald-400 transition-all">
                                        Masuk Dashboard Admin
                                    </a>
                                @endif
                            @endauth

                        </div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60">
                        <div class="text-3xl font-bold text-sky-400 mb-2">95%</div>
                        <div class="text-sm text-slate-300">Akurasi Analisis</div>
                    </div>
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60">
                        <div class="text-3xl font-bold text-emerald-400 mb-2">10K+</div>
                        <div class="text-sm text-slate-300">CV Teranalisis</div>
                    </div>
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60">
                        <div class="text-3xl font-bold text-cyan-400 mb-2">&lt;30s</div>
                        <div class="text-sm text-slate-300">Waktu Analisis</div>
                    </div>
                </div>
            </div>

            {{-- Features Section --}}
            <div class="mb-20">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-slate-50 mb-4">
                        Fitur Unggulan
                    </h2>
                    <p class="text-slate-400 max-w-2xl mx-auto">
                        Teknologi AI canggih untuk memberikan insight terbaik dari CV kamu
                    </p>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    
                    {{-- Feature 1: AI Analysis --}}
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60 hover:border-sky-500/50 transition-all">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-500/20 border border-sky-500/30">
                                    <svg class="h-6 w-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-slate-200 mb-2">
                                    Analisis AI Cerdas
                                </h3>
                                <p class="text-sm text-slate-400">
                                    Menggunakan teknologi AI untuk menganalisis skill, pengalaman, dan potensi kamu secara mendalam.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 2: Division Match --}}
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60 hover:border-emerald-500/50 transition-all">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-500/20 border border-emerald-500/30">
                                    <svg class="h-6 w-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-slate-200 mb-2">
                                    Rekomendasi Divisi
                                </h3>
                                <p class="text-sm text-slate-400">
                                    Dapatkan rekomendasi divisi yang paling cocok dengan profile dan skill set kamu.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 3: Skill Gap --}}
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60 hover:border-cyan-500/50 transition-all">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-500/20 border border-cyan-500/30">
                                    <svg class="h-6 w-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-slate-200 mb-2">
                                    Skill Gap Analysis
                                </h3>
                                <p class="text-sm text-slate-400">
                                    Identifikasi skill yang perlu dikembangkan untuk meningkatkan peluang diterima.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 4: ATS Score --}}
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60 hover:border-violet-500/50 transition-all">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-500/20 border border-violet-500/30">
                                    <svg class="h-6 w-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-slate-200 mb-2">
                                    ATS Compatibility
                                </h3>
                                <p class="text-sm text-slate-400">
                                    Cek seberapa siap CV kamu untuk sistem ATS (Applicant Tracking System).
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 5: Detailed Feedback --}}
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60 hover:border-amber-500/50 transition-all">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-500/20 border border-amber-500/30">
                                    <svg class="h-6 w-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-slate-200 mb-2">
                                    Feedback Detail
                                </h3>
                                <p class="text-sm text-slate-400">
                                    Dapatkan feedback spesifik tentang kekuatan dan area yang perlu ditingkatkan.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Feature 6: Compare --}}
                    <div class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 shadow-lg shadow-slate-950/60 hover:border-rose-500/50 transition-all">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-500/20 border border-rose-500/30">
                                    <svg class="h-6 w-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-semibold text-slate-200 mb-2">
                                    Compare Versions
                                </h3>
                                <p class="text-sm text-slate-400">
                                    Bandingkan berbagai versi CV kamu untuk melihat perkembangan dan improvement.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- How It Works Section --}}
            <div class="mb-20">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-slate-50 mb-4">
                        Cara Kerja
                    </h2>
                    <p class="text-slate-400 max-w-2xl mx-auto">
                        Tiga langkah mudah untuk mendapatkan analisis CV profesional
                    </p>
                </div>

                <div class="grid gap-8 md:grid-cols-3">
                    <div class="text-center">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-sky-400 to-cyan-300 text-slate-900 font-bold text-xl mb-4 shadow-lg shadow-sky-500/50">
                            1
                        </div>
                        <h3 class="text-lg font-semibold text-slate-200 mb-2">Upload CV</h3>
                        <p class="text-sm text-slate-400">
                            Upload file CV kamu dalam format PDF, DOCX, atau input manual
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-cyan-300 text-slate-900 font-bold text-xl mb-4 shadow-lg shadow-emerald-500/50">
                            2
                        </div>
                        <h3 class="text-lg font-semibold text-slate-200 mb-2">AI Analysis</h3>
                        <p class="text-sm text-slate-400">
                            AI kami akan menganalisis skill, pengalaman, dan kesesuaian divisi
                        </p>
                    </div>

                    <div class="text-center">
                        <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-br from-violet-400 to-cyan-300 text-slate-900 font-bold text-xl mb-4 shadow-lg shadow-violet-500/50">
                            3
                        </div>
                        <h3 class="text-lg font-semibold text-slate-200 mb-2">Get Insights</h3>
                        <p class="text-sm text-slate-400">
                            Terima hasil analisis lengkap dengan rekomendasi dan actionable feedback
                        </p>
                    </div>
                </div>
            </div>

            {{-- CTA Section --}}
            <div class="relative overflow-hidden rounded-3xl border border-sky-500/50 bg-gradient-to-br from-sky-900/40 to-slate-900/40 p-12 text-center shadow-2xl shadow-sky-900/60">
                <div class="absolute inset-0 pointer-events-none opacity-30"
                     style="background: radial-gradient(circle at 50% 50%, rgba(56,189,248,.4), transparent 70%);">
                </div>
                
                <div class="relative">
                    <h2 class="text-3xl font-bold text-slate-50 mb-4">
                        Siap Menganalisis CV Kamu?
                    </h2>
                    <p class="text-slate-300 mb-8 max-w-2xl mx-auto">
                        Dapatkan insight profesional tentang CV kamu dalam hitungan detik. Gratis dan mudah digunakan.
                    </p>
                    
                    @auth
                        @if(auth()->user()->role === 'user')
                            <a href="{{ route('cv.create') }}"
                            class="inline-flex items-center justify-center rounded-full bg-white px-8 py-3 text-base font-semibold text-slate-900 shadow-lg hover:bg-slate-100 transition-all">
                                Mulai Analisis CV
                            </a>
                        @else
                            <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center justify-center rounded-full bg-white px-8 py-3 text-base font-semibold text-slate-900 shadow-lg hover:bg-slate-100 transition-all">
                                Buka Dashboard Admin
                            </a>
                        @endif
                    @endauth

                </div>
            </div>

        </div>

        {{-- Footer --}}
        <footer class="border-t border-slate-800 mt-20">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="h-6 w-6 rounded-lg bg-gradient-to-br from-sky-400 to-cyan-300 flex items-center justify-center">
                            <svg class="h-4 w-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-slate-300">CareerLens<span class="text-sky-400">.AI</span></span>
                    </div>
                    
                    <p class="text-xs text-slate-500">
                        © {{ date('Y') }} CareerLens.AI. Powered by AI Technology.
                    </p>
                </div>
            </div>
        </footer>

    </body>
</html>