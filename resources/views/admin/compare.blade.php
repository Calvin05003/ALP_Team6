<x-app-layout>
    <div class="py-10 max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Compare CV (Admin)</h1>

        <form action="{{ route('admin.cv.compare') }}" method="POST" class="grid md:grid-cols-2 gap-6 bg-slate-900/60 border border-slate-800 rounded-xl p-6">
            @csrf

            {{-- CV A --}}
            <div>
                <label class="block text-sm mb-1">CV A</label>
                <select name="cv_a" required class="w-full rounded-md bg-slate-950 border border-slate-700 text-sm">
                    @foreach ($analyses as $cv)
                        <option value="{{ $cv->id }}">
                            {{ $cv->cvSubmission->original_filename }} - {{ $cv->cvSubmission->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- CV B --}}
            <div>
                <label class="block text-sm mb-1">CV B</label>
                <select name="cv_b" required class="w-full rounded-md bg-slate-950 border border-slate-700 text-sm">
                    @foreach ($analyses as $cv)
                        <option value="{{ $cv->id }}">
                            {{ $cv->cvSubmission->original_filename }} - {{ $cv->cvSubmission->user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="px-6 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 transition font-medium">
                    Compare CV
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
