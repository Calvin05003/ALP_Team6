<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">
        <div class="max-w-6xl mx-auto py-10 px-4">

            <h1 class="text-2xl font-semibold mb-8">CV Comparison</h1>

            <div class="grid md:grid-cols-2 gap-6">

                {{-- CV A --}}
                <div class="rounded-2xl border border-sky-500/40 bg-slate-900/70 p-6">
                    <h2 class="text-lg font-semibold text-sky-400 mb-2">CV A</h2>
                    <p class="text-sm text-slate-300 mb-4">
                        {{ $a->cvSubmission->original_filename }}
                    </p>
                    <p class="text-4xl font-semibold text-sky-400">
                        {{ $a->resume_score }}/100
                    </p>
                </div>

                {{-- CV B --}}
                <div class="rounded-2xl border border-emerald-500/40 bg-slate-900/70 p-6">
                    <h2 class="text-lg font-semibold text-emerald-400 mb-2">CV B</h2>
                    <p class="text-sm text-slate-300 mb-4">
                        {{ $b->cvSubmission->original_filename }}
                    </p>
                    <p class="text-4xl font-semibold text-emerald-400">
                        {{ $b->resume_score }}/100
                    </p>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
