<section
    class="w-full max-w-xl px-6 py-4 mx-auto mt-16 mb-16 space-y-6 rounded-lg w-xl backdrop-blur-sm bg-indigo-900/20">
    <h2 class="text-3xl font-bold text-center text-white">Tambahkan karya</h2>
    <form id="form" action="{{ route('workStore') }}" method="POST" enctype="multipart/form-data">
        @csrf <div class="mb-6">
            <label for="title" class="block mb-2 text-sm font-bold text-white text-gray-700">Judul Karya</label>
            <input type="text"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-500 @error('title') border-red-500 @enderror"
                id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <p class="mt-2 text-xs italic text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="paragraph" class="block mb-2 text-sm font-bold text-white text-gray-700">Deskripsi</label>
            <div class="w-full max-w-3xl p-6 bg-white rounded-lg shadow-lg">
                {{-- <h1 class="mb-4 text-2xl font-semibold text-gray-900">Rich Text Editor with Flowbite</h1> --}}
                <!-- Toolbar -->
                <div class="flex px-2 py-1 mb-3 space-x-2 bg-white border border-gray-300 rounded-md">
                    <button type="button" aria-label="Bold" title="Bold (Ctrl+B)"
                        class="px-3 py-1 text-lg font-bold text-gray-700 rounded hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onclick="execCmd('bold')">
                        B
                        <span class="sr-only">Bold</span>
                    </button>
                    <button type="button" aria-label="Italic" title="Italic (Ctrl+I)"
                        class="px-3 py-1 text-lg italic text-gray-700 rounded hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onclick="execCmd('italic')">
                        I
                        <span class="sr-only">Italic</span>
                    </button>
                    <button type="button" aria-label="Underline" title="Underline (Ctrl+U)"
                        class="px-2 py-1 text-gray-700 rounded hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onclick="execCmd('underline')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 underline" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 4v6a5 5 0 0010 0V4m-5 16h8" />
                        </svg>
                        <span class="sr-only">Underline</span>
                    </button>
                    <button type="button" aria-label="Unordered List" title="Unordered List"
                        class="px-2 py-1 text-gray-700 rounded hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onclick="execCmd('insertUnorderedList')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 9h12M6 15h12M6 6h.01M6 12h.01M6 18h.01" />
                        </svg>
                        <span class="sr-only">Unordered List</span>
                    </button>
                    <button type="button" aria-label="Ordered List" title="Ordered List"
                        class="px-2 py-1 text-gray-700 rounded hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onclick="execCmd('insertOrderedList')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 6h.01M9 12h.01M9 18h.01M13 6h7M13 12h7M13 18h7" />
                        </svg>
                        <span class="sr-only">Ordered List</span>
                    </button>
                    <button type="button" aria-label="Clear Formatting" title="Clear Formatting"
                        class="px-2 py-1 text-gray-700 rounded hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
                        onclick="clearFormatting()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="sr-only">Clear Formatting</span>
                    </button>
                </div>
                <!-- Editable content area -->
                <div id="editor" contenteditable="true" spellcheck="true"
                    class="min-h-[150px] border border-gray-300 rounded-md p-4 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-gray-900 prose max-w-full overflow-auto">
                </div>

                <!-- Hidden textarea to submit content -->
                <textarea name="paragraph" id="paragraph" class="hidden" rows="10" value="{{ old('paragraph') }}"></textarea>
            </div>
            @error('paragraph')
                <p class="mt-2 text-xs italic text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="image" class="block mb-2 text-sm font-bold text-white text-gray-700">Upload Gambar</label>
            <input type="file" required
                class="block w-full text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                id="image" name="image" accept="image/*">
            <p class="mt-2 text-xs text-white">Format yang diizinkan: JPG, PNG, GIF. Maks. 5MB.</p>
            @error('image')
                <p class="mt-2 text-xs italic text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="video" class="block mb-2 text-sm font-bold text-white text-gray-700">Upload Video
                (Opsional)</label>
            <input type="file"
                class="block w-full text-sm text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                id="video" name="video" accept="video/*">
            <p class="mt-2 text-xs text-white">Format yang diizinkan: MP4, MOV, AVI. Maks. 20MB.</p>
            @error('video')
                <p class="mt-2 text-xs italic text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <button type="submit"
                class="w-full px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                Simpan Karya
            </button>
        </div>
    </form>
    @push('scripts')
        <script src="{{ asset('js/front/index.js') }}"></script>
    @endpush
</section>
