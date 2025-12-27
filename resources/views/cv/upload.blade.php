<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">
        <div class="max-w-4xl mx-auto py-10 px-4">

            <h1 class="text-2xl font-semibold mb-6">
                Analyze CV dengan AI
            </h1>

            <form
                action="{{ route('cv.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6 bg-slate-900/60 border border-slate-800 rounded-xl p-6">

                @csrf

                {{-- Upload CV --}}
                <div>
                    <label class="block text-sm mb-1">Upload CV (PDF)</label>
                    <input
                        type="file"
                        name="cv"
                        accept=".pdf"
                        required
                        class="w-full rounded-md bg-slate-950 border border-slate-700 text-sm">
                </div>

                {{-- Bahasa --}}
                <div>
                    <label class="block text-sm mb-1">Bahasa Analisis</label>
                    <select
                        name="language"
                        required
                        class="w-full rounded-md bg-slate-950 border border-slate-700 text-sm">
                        <option value="id">Bahasa Indonesia</option>
                        <option value="en">English</option>
                    </select>
                </div>

                {{-- Jenis Analisis --}}
                <div>
                    <label class="block text-sm mb-1">Jenis Analisis</label>
                    <select
                        name="analysis_type"
                        required
                        class="w-full rounded-md bg-slate-950 border border-slate-700 text-sm">
                        <option value="kepanitiaan">Kepanitiaan</option>
                        <option value="professional">Professional</option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="px-6 py-2 rounded-lg bg-sky-600 hover:bg-sky-500 transition font-medium">
                    Analyze CV
                </button>
            </form>
        </div>
    </div>

    {{-- LOADING OVERLAY --}}
    <div
        id="loading-overlay"
        class="fixed inset-0 z-50 hidden items-center justify-center
               bg-slate-950/80 backdrop-blur-sm">

        <div class="flex flex-col items-center gap-4">
            <svg class="animate-spin h-10 w-10 text-sky-400" viewBox="0 0 24 24">
                <circle class="opacity-20" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"/>
                <path class="opacity-80" fill="currentColor"
                      d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
            </svg>

            <p class="text-sm text-slate-300">
                Menganalisis CV dengan AI…
            </p>
        </div>
    </div>

    <script>
        const form = document.querySelector('form[action="{{ route('cv.store') }}"]');
        const overlay = document.getElementById('loading-overlay');

        if (form) {
            form.addEventListener('submit', () => {
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
            });
        }
    </script>
</x-app-layout>
