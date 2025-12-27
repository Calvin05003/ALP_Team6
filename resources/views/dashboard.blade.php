<x-app-layout>
    <div class="py-10 max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-slate-100">Dashboard User</h1>

        <div class="grid sm:grid-cols-2 gap-6">
            {{-- Analyze CV Card --}}
            <a href="{{ route('cv.create') }}" 
               class="group block p-6 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-400 shadow-lg hover:scale-105 hover:shadow-2xl transition-transform duration-300">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <div>
                        <h2 class="text-xl font-semibold text-white">Analyze CV</h2>
                        <p class="text-sm text-indigo-100 mt-1">Upload and analyze your CV using AI insights.</p>
                    </div>
                </div>
            </a>

            {{-- History CV Card --}}
            <a href="{{ route('cv.history') }}" 
               class="group block p-6 rounded-2xl bg-gradient-to-r from-slate-700 to-slate-900 shadow-lg hover:scale-105 hover:shadow-2xl transition-transform duration-300">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white group-hover:animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v6h6M21 21v-6h-6M3 21h6v-6M21 3h-6v6" />
                    </svg>
                    <div>
                        <h2 class="text-xl font-semibold text-white">History CV</h2>
                        <p class="text-sm text-slate-300 mt-1">View all your analyzed CVs and results.</p>
                    </div>
                </div>
            </a>
        </div>

        {{-- Optional: Add stats --}}
        <div class="mt-10 grid sm:grid-cols-3 gap-6">
            <div class="p-4 rounded-2xl bg-slate-800 text-white shadow-md">
                <h3 class="text-sm font-medium">Total CVs Analyzed</h3>
                <p class="text-2xl font-bold mt-2">{{ auth()->user()->cvSubmissions()->count() }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-800 text-white shadow-md">
                <h3 class="text-sm font-medium">Last CV Score</h3>
                <p class="text-2xl font-bold mt-2">
                    {{ optional(auth()->user()->cvSubmissions()->latest()->first()?->analysis)->resume_score ?? '-' }}
                </p>
            </div>
            <div class="p-4 rounded-2xl bg-slate-800 text-white shadow-md">
                <h3 class="text-sm font-medium">Last CV Uploaded</h3>
                <p class="text-2xl font-bold mt-2">
                    {{ optional(auth()->user()->cvSubmissions()->latest()->first())->created_at?->format('d M Y') ?? '-' }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
