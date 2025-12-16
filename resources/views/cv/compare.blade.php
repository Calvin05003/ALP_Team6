<x-app-layout>
    <div class="min-h-screen bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100">
        <div class="max-w-4xl mx-auto py-10 px-4">

            <h1 class="text-2xl font-semibold mb-6">Compare CV Versions</h1>

            <form method="POST" action="{{ route('cv.compare') }}"
                  class="rounded-2xl border border-slate-700/70 bg-slate-900/70 p-6 space-y-4">
                @csrf

                <input type="hidden" name="cv_a" value="{{ $current->id }}">

                <div>
                    <label class="text-xs text-slate-400">Current CV</label>
                    <p class="text-sm font-semibold text-sky-400">
                        {{ $current->cvSubmission->original_filename }}
                    </p>
                </div>

                <div>
                    <label class="text-xs text-slate-400">Compare With</label>
                    <select name="cv_b"
                            class="w-full mt-1 rounded-xl bg-slate-950 border border-slate-700 text-sm">
                        @foreach($histories as $h)
                            <option value="{{ $h->id }}">
                                {{ $h->cvSubmission->original_filename }}
                                (Score {{ $h->resume_score }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="rounded-full bg-emerald-500 px-6 py-2 text-sm font-semibold text-white">
                    Compare
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
