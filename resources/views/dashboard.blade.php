<x-app-layout>

    {{-- Main Container --}}
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">
        
        {{-- Background Glow Effects --}}
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-sky-600/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-emerald-600/10 blur-[100px] rounded-full pointer-events-none"></div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                {{-- 1. Welcome Hero Section --}}
                <div class="relative overflow-hidden rounded-3xl bg-slate-900/60 backdrop-blur-xl border border-slate-700/50 p-8 sm:p-12 mb-10 shadow-2xl">
                    {{-- Inner Glow --}}
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-gradient-to-br from-sky-500/20 to-emerald-500/20 blur-3xl rounded-full"></div>
                    
                    <div class="relative z-10">
                        <h3 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                            Selamat Datang, <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-emerald-400">{{ Auth::user()->name }}!</span> 👋
                        </h3>
                        <p class="text-slate-400 text-lg max-w-2xl leading-relaxed">
                            Pusat kendali karier Anda. Mulai analisis CV baru, pantau perkembangan skill, atau kelola profil Anda dari sini.
                        </p>
                    </div>
                </div>

                {{-- 2. Action Grid Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    {{-- Card 1: Upload / Mulai Baru (Paling Menonjol) --}}
                    <a href="{{ route('cv.create') }}" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 to-slate-950 border border-slate-700 p-6 hover:border-sky-500 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_30px_rgba(14,165,233,0.15)]">
                        <div class="absolute inset-0 bg-gradient-to-r from-sky-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative z-10 flex flex-col h-full">
                            <div class="w-12 h-12 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center mb-4 group-hover:bg-sky-500 group-hover:border-sky-400 group-hover:text-white text-sky-400 transition-all duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                            <h4 class="text-xl font-bold text-slate-100 mb-2 group-hover:text-sky-400 transition-colors">Analisis CV Baru</h4>
                            <p class="text-sm text-slate-400 leading-relaxed">
                                Upload CV terbaru Anda atau input manual untuk mendapatkan analisis AI instan dan rekomendasi divisi.
                            </p>
                            <div class="mt-auto pt-4 flex items-center text-sm font-semibold text-sky-500 group-hover:translate-x-2 transition-transform">
                                Mulai Sekarang <span class="ml-2">&rarr;</span>
                            </div>
                        </div>
                    </a>

                    {{-- Card 2: Riwayat / Hasil --}}
                    <a href="{{ route('cv.history') }}" class="group relative overflow-hidden rounded-2xl bg-slate-900/60 backdrop-blur-sm border border-slate-700 p-6 hover:bg-slate-800 hover:border-emerald-500/50 transition-all duration-300">
                        <div class="relative z-10 flex flex-col h-full">
                            <div class="w-12 h-12 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center mb-4 group-hover:text-emerald-400 text-emerald-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h4 class="text-xl font-bold text-slate-100 mb-2">Riwayat Pemeriksaan</h4>
                            <p class="text-sm text-slate-400 leading-relaxed">
                                Lihat kembali hasil analisis sebelumnya, bandingkan skor, dan pelajari feedback yang tersimpan.
                            </p>
                        </div>
                    </a>

                    {{-- Card 3: Profil Akun --}}
                    <a href="{{ route('profile.edit') }}" class="group relative overflow-hidden rounded-2xl bg-slate-900/60 backdrop-blur-sm border border-slate-700 p-6 hover:bg-slate-800 hover:border-indigo-500/50 transition-all duration-300">
                        <div class="relative z-10 flex flex-col h-full">
                            <div class="w-12 h-12 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center mb-4 group-hover:text-indigo-400 text-indigo-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h4 class="text-xl font-bold text-slate-100 mb-2">Profil Akun</h4>
                            <p class="text-sm text-slate-400 leading-relaxed">
                                Kelola data pribadi, ubah password, dan pengaturan akun CareerLens Anda.
                            </p>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>