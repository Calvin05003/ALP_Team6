{{-- resources/views/cv/upload.blade.php --}}
<x-app-layout>
    <div class="max-w-3xl mx-auto py-12">

        <div class="bg-white/70 backdrop-blur-xl shadow-2xl border border-gray-100 
                    rounded-2xl p-10">

            <h1 class="text-4xl font-extrabold text-gray-900 mb-3 tracking-tight">
                Upload Your CV
            </h1>

            <p class="text-gray-600 mb-8 text-md">
                CareerLens.AI will analyze your CV and generate insights, recommendations,
                and job matching suggestions.
            </p>

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                    <ul class="list-disc pl-5 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Success --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('cv.store') }}" method="POST"
                  enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Input Mode --}}
                <div>
                    <label class="font-semibold block mb-3 text-gray-800 text-lg">Choose Input Method</label>

                    <div class="flex items-center space-x-6">

                        {{-- Radio 1 --}}
                        <label class="flex items-center space-x-3 cursor-pointer group">

                            <input type="radio"
                                   name="input_mode"
                                   value="file"
                                   class="hidden peer"
                                   {{ old('input_mode', 'file') === 'file' ? 'checked' : '' }}>

                            <div class="w-5 h-5 rounded-full border border-gray-400 
                                        peer-checked:border-indigo-600 peer-checked:bg-indigo-600
                                        transition"></div>

                            <span class="text-gray-700 group-hover:text-indigo-600 transition">
                                Upload CV File
                            </span>
                        </label>

                        {{-- Radio 2 --}}
                        <label class="flex items-center space-x-3 cursor-pointer group">

                            <input type="radio"
                                   name="input_mode"
                                   value="manual"
                                   class="hidden peer"
                                   {{ old('input_mode') === 'manual' ? 'checked' : '' }}>

                            <div class="w-5 h-5 rounded-full border border-gray-400 
                                        peer-checked:border-indigo-600 peer-checked:bg-indigo-600
                                        transition"></div>

                            <span class="text-gray-700 group-hover:text-indigo-600 transition">
                                Manual Input
                            </span>
                        </label>
                    </div>
                </div>

                {{-- File Upload --}}
                <div id="file-input-wrapper"
                     class="transition-all duration-300 ease-in-out">
                    <label class="block mb-2 font-semibold text-gray-800 text-lg">Upload CV File</label>

                    <input type="file"
                           name="cv_file"
                           class="border border-gray-300 rounded-xl w-full px-4 py-3 bg-white 
                                  shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                  transition">

                    <p class="text-sm text-gray-500 mt-2">
                        Max 5 MB | Accepted: PDF, DOC, DOCX, TXT
                    </p>
                </div>

                {{-- Manual Text Input --}}
                <div id="manual-input-wrapper" style="display:none;"
                     class="transition-all duration-300 ease-in-out">
                    <label class="block mb-2 font-semibold text-gray-800 text-lg">Manual CV Text</label>

                    <textarea name="cv_text" rows="10"
                              class="border border-gray-300 rounded-xl w-full px-4 py-3 bg-white 
                                     shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                     transition">{{ old('cv_text') }}</textarea>

                    <p class="text-sm text-gray-500 mt-2">
                        Paste your CV content here if you're not uploading a file.
                    </p>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-500 via-purple-600 to-pink-500 
                               text-white px-6 py-3 rounded-xl font-semibold text-lg shadow-md 
                               hover:opacity-90 transition">
                    Analyze CV
                </button>
            </form>
        </div>
    </div>

    <script>
        const fileWrapper = document.getElementById('file-input-wrapper');
        const manualWrapper = document.getElementById('manual-input-wrapper');
        const radios = document.querySelectorAll('input[name="input_mode"]');

        function updateMode() {
            const mode = document.querySelector('input[name="input_mode"]:checked').value;
            fileWrapper.style.display = mode === 'file' ? 'block' : 'none';
            manualWrapper.style.display = mode === 'manual' ? 'block' : 'none';
        }

        radios.forEach(r => r.addEventListener('change', updateMode));
        updateMode();
    </script>

</x-app-layout>
