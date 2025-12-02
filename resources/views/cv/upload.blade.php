{{-- resources/views/cv/upload.blade.php --}}
<x-app-layout>
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">CareerLens.AI – Upload CV</h1>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('cv.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="font-semibold block mb-2">Input mode</label>
                <label class="inline-flex items-center mr-4">
                    <input type="radio" name="input_mode" value="file" {{ old('input_mode', 'file') === 'file' ? 'checked' : '' }}>
                    <span class="ml-2">Upload CV file (PDF/DOCX/TXT)</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="input_mode" value="manual" {{ old('input_mode') === 'manual' ? 'checked' : '' }}>
                    <span class="ml-2">Manual text input</span>
                </label>
            </div>

            <div id="file-input-wrapper">
                <label class="block mb-2 font-semibold">CV File</label>
                <input type="file" name="cv_file" class="border rounded w-full px-3 py-2">
                <p class="text-sm text-gray-500 mt-1">Maksimal 5 MB. Format: pdf, doc, docx, txt.</p>
            </div>

            <div id="manual-input-wrapper" style="display: none;">
                <label class="block mb-2 font-semibold">CV Text (Manual)</label>
                <textarea name="cv_text" rows="10" class="border rounded w-full px-3 py-2">{{ old('cv_text') }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Copy-paste isi CV kamu di sini jika tidak upload file.</p>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                Analyze CV
            </button>
        </form>
    </div>

    <script>
        const fileWrapper = document.getElementById('file-input-wrapper');
        const manualWrapper = document.getElementById('manual-input-wrapper');
        const radios = document.querySelectorAll('input[name="input_mode"]');

        function updateMode() {
            const mode = document.querySelector('input[name="input_mode"]:checked').value;
            if (mode === 'file') {
                fileWrapper.style.display = 'block';
                manualWrapper.style.display = 'none';
            } else {
                fileWrapper.style.display = 'none';
                manualWrapper.style.display = 'block';
            }
        }

        radios.forEach(r => r.addEventListener('change', updateMode));
        updateMode();
    </script>
</x-app-layout>