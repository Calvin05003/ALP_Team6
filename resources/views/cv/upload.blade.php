{{-- resources/views/cv/upload.blade.php --}}
<x-app-layout>
    {{-- Main Wrapper: Gradient Background Gelap (Sama seperti Result) --}}
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 relative overflow-hidden">

        {{-- Background Decoration (Futuristic Glow - Lebih Halus) --}}
        <div class="absolute top-[-10%] left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-sky-600/10 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-emerald-600/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="relative flex items-center justify-center min-h-[85vh] py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-2xl">
                
                {{-- Header Text --}}
                <div class="text-center mb-10">
                    <p class="text-xs font-bold tracking-[0.3em] text-sky-500/80 uppercase mb-3">
                        CareerLens.AI
                    </p>
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight drop-shadow-lg">
                        Upload Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-emerald-400">CV</span>
                    </h1>
                    <p class="mt-4 text-slate-400 text-sm leading-relaxed max-w-lg mx-auto">
                        Analisis AI canggih untuk membedah potensi karier, rekomendasi divisi, dan optimasi ATS dalam hitungan detik.
                    </p>
                </div>

                {{-- Main Card (Glassmorphism + Gradient Gelap) --}}
                <div class="relative group">
                    {{-- Neon Border Effect (Animated) --}}
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-sky-500 via-indigo-500 to-emerald-500 rounded-3xl opacity-20 group-hover:opacity-40 blur transition duration-700"></div>
                    
                    {{-- Card Content --}}
                    <div class="relative bg-gradient-to-br from-slate-900 to-slate-950/80 backdrop-blur-xl rounded-3xl border border-slate-700/50 p-6 sm:p-10 shadow-2xl shadow-black/50">
                        
                        {{-- Notifications --}}
                        @if ($errors->any())
                            <div class="mb-6 p-4 rounded-xl bg-rose-950/40 border border-rose-500/30 text-rose-300 text-sm shadow-inner">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="mb-6 p-4 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 text-sm font-medium shadow-inner">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('cv.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                            @csrf

                            {{-- 1. Selector Mode (Futuristic Tabs) --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Metode Input</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    {{-- Option: File --}}
                                    <label class="relative cursor-pointer group/item">
                                        <input type="radio" name="input_mode" value="file" class="peer sr-only" {{ old('input_mode', 'file') === 'file' ? 'checked' : '' }}>
                                        <div class="p-4 rounded-2xl border border-slate-800 bg-slate-950/60 hover:bg-slate-900 hover:border-sky-500/50 transition-all duration-300 peer-checked:border-sky-500 peer-checked:bg-sky-500/10 peer-checked:shadow-[0_0_15px_rgba(14,165,233,0.15)]">
                                            <div class="flex items-center gap-4">
                                                <div class="p-3 rounded-xl bg-slate-900 border border-slate-700 text-slate-400 group-hover/item:text-sky-400 peer-checked:bg-sky-500 peer-checked:border-sky-400 peer-checked:text-white transition-all">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-200 group-hover/item:text-sky-200">Upload File</div>
                                                    <div class="text-[10px] uppercase font-semibold text-slate-500 tracking-wide">PDF / DOCX</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    {{-- Option: Manual --}}
                                    <label class="relative cursor-pointer group/item">
                                        <input type="radio" name="input_mode" value="manual" class="peer sr-only" {{ old('input_mode') === 'manual' ? 'checked' : '' }}>
                                        <div class="p-4 rounded-2xl border border-slate-800 bg-slate-950/60 hover:bg-slate-900 hover:border-emerald-500/50 transition-all duration-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 peer-checked:shadow-[0_0_15px_rgba(16,185,129,0.15)]">
                                            <div class="flex items-center gap-4">
                                                <div class="p-3 rounded-xl bg-slate-900 border border-slate-700 text-slate-400 group-hover/item:text-emerald-400 peer-checked:bg-emerald-500 peer-checked:border-emerald-400 peer-checked:text-white transition-all">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-200 group-hover/item:text-emerald-200">Input Manual</div>
                                                    <div class="text-[10px] uppercase font-semibold text-slate-500 tracking-wide">Copy Paste</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- 2. Content Area --}}
                            <div class="relative min-h-[180px]">
                                
                                {{-- A. File Upload (Dark Tech Look) --}}
                                <div id="file-input-wrapper" class="transition-all duration-500">
                                    <label for="cv_file" class="relative flex flex-col items-center justify-center w-full h-52 border border-dashed border-slate-700/80 rounded-2xl cursor-pointer bg-slate-950/40 hover:bg-slate-900/60 hover:border-sky-500/50 hover:shadow-[inset_0_0_20px_rgba(14,165,233,0.05)] transition-all group overflow-hidden">
                                        {{-- Grid Pattern Background --}}
                                        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                                        
                                        <div class="relative flex flex-col items-center justify-center pt-5 pb-6 z-10">
                                            <div class="mb-4 p-4 rounded-full bg-slate-900 border border-slate-800 shadow-xl group-hover:scale-110 group-hover:border-sky-500/50 group-hover:shadow-sky-500/20 transition-all duration-300">
                                                <svg class="w-8 h-8 text-slate-400 group-hover:text-sky-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                                </svg>
                                            </div>
                                            <p class="mb-2 text-sm text-slate-300 font-medium">Klik untuk upload file</p>
                                            <p class="text-xs text-slate-500">atau drag & drop di area ini</p>
                                            
                                            <div id="filename-display" class="mt-4 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-xs font-semibold text-emerald-400 hidden animate-fade-in-up">
                                                File terpilih
                                            </div>
                                        </div>
                                        <input id="cv_file" type="file" name="cv_file" class="hidden" accept=".pdf,.doc,.docx,.txt" />
                                    </label>
                                </div>

                                {{-- B. Manual Input (Code Editor Look) --}}
                                <div id="manual-input-wrapper" class="hidden transition-all duration-500">
                                    <div class="relative group">
                                        <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500/20 to-sky-500/20 rounded-2xl opacity-0 group-focus-within:opacity-100 transition duration-500 blur"></div>
                                        <textarea name="cv_text" rows="9" 
                                            class="relative block p-5 w-full text-sm text-slate-200 bg-slate-950 rounded-2xl border border-slate-800 focus:border-slate-700 focus:ring-0 placeholder-slate-600 font-mono leading-relaxed transition-all shadow-inner" 
                                            placeholder="// Paste isi CV Anda di sini...&#10;Contoh:&#10;&#10;PENGALAMAN KERJA&#10;- Software Engineer di Tech Corp (2020-2023)..."></textarea>
                                        
                                        <div class="absolute bottom-4 right-4 px-2 py-1 bg-slate-900 border border-slate-700 rounded text-[10px] text-slate-500 font-mono">
                                            TXT MODE
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Button (Glowing Gradient) --}}
                            <button type="submit" 
                                class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-2xl text-white overflow-hidden transition-all hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-900 focus:ring-sky-500">
                                
                                {{-- Button Background --}}
                                <div class="absolute inset-0 bg-gradient-to-r from-sky-600 via-indigo-600 to-sky-600 bg-size-200 transition-all duration-500 group-hover:bg-pos-100"></div>
                                <div class="absolute inset-0 bg-white/0 group-hover:bg-white/10 transition-all"></div>
                                
                                {{-- Button Content --}}
                                <div class="relative flex items-center gap-2">
                                    <span>Jalankan Analisis AI</span>
                                    <svg class="w-4 h-4 text-indigo-200 group-hover:text-white group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </div>
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[name="input_mode"]');
            const fileWrapper = document.getElementById('file-input-wrapper');
            const manualWrapper = document.getElementById('manual-input-wrapper');
            const fileInput = document.getElementById('cv_file');
            const filenameDisplay = document.getElementById('filename-display');

            // Toggle Visual Mode
            function updateMode() {
                const mode = document.querySelector('input[name="input_mode"]:checked').value;
                if(mode === 'file') {
                    fileWrapper.classList.remove('hidden');
                    manualWrapper.classList.add('hidden');
                    fileWrapper.style.opacity = 0;
                    fileWrapper.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        fileWrapper.style.opacity = 1;
                        fileWrapper.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    fileWrapper.classList.add('hidden');
                    manualWrapper.classList.remove('hidden');
                    manualWrapper.style.opacity = 0;
                    manualWrapper.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        manualWrapper.style.opacity = 1;
                        manualWrapper.style.transform = 'translateY(0)';
                    }, 50);
                }
            }

            radios.forEach(radio => radio.addEventListener('change', updateMode));
            
            // File Selection Feedback
            fileInput.addEventListener('change', function(e) {
                if(this.files && this.files[0]) {
                    filenameDisplay.textContent = this.files[0].name;
                    filenameDisplay.classList.remove('hidden');
                } else {
                    filenameDisplay.classList.add('hidden');
                }
            });

            updateMode();
        });
    </script>
</x-app-layout>