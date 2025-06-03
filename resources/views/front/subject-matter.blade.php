@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp
<x-front-layout>
    <section class="max-w-5xl px-6 pt-12 pb-16 mx-auto mt-32 mb-24 space-y-10 shadow-xl bg-white/90 rounded-3xl">
        <h2 class="mb-8 text-3xl font-bold text-center text-indigo-900 animate__animated animate__fadeInDown">
            {{ $data->name }}</h2>
        <div class="grid grid-cols-1 gap-6 mx-8 md:grid-cols-2">
            @if ($data->file_path)
                <div
                    class="p-3 overflow-hidden shadow-lg rounded-xl ring-2 ring-indigo-300 ring-offset-2 ring-offset-indigo-50">
                    <div class="flex justify-between mb-6">
                        <p
                            class="max-w-lg text-base font-semibold text-gray-800 text-wrap animate__animated animate__fadeInUp delay-1">
                            Media PDF
                        </p>
                        <button class="p-2 text-xs font-semibold text-white bg-red-500 rounded-md hover:bg-red-700"
                            @click="window.open('{{ Storage::url($data->file_path) }}','_blank')">Buka PDF di tab
                            baru</button>
                    </div>
                    <div class="min-w-[400px] h-[600px] w-[calc((9/16)*100%)]">
                        <embed src="{{ Storage::url($data->file_path) }}" type="application/pdf" class="w-full h-full"
                            aria-label="Embedded PDF document" />
                    </div>
                </div>
            @endif
            <div class="flex flex-col gap-5 ">
                @if ($data->video_link || $data->video_path)
                    <div
                        class="p-3 overflow-hidden shadow-lg h-fit rounded-xl ring-2 ring-indigo-300 ring-offset-2 ring-offset-indigo-50">
                        <p class="mb-6 text-xs text-gray-800 animate__animated animate__fadeInUp delay-1">
                            <span class="text-base font-semibold">Media Video</span> <br>
                            Source : <a class="hover:underline hover:text-blue-500"
                                href="{{ $data->video_link }}">{{ $data->video_link }}</a>
                        </p>
                        <iframe class="w-[calc((9/16)*100%)] h-[calc((5/16)*100%)] min-w-[400px] min-h-[225px]"
                            src="{{ Str::replace(['watch?=', 'watch?v='], 'embed/', $data->video_link) }}"
                            title="YouTube video player"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen loading="lazy"></iframe>
                    </div>
                @endif
                @if ($data->is_has_assigment)
                    <div x-data="{ status: {{ $assigment ? 'true' : 'false' }}, open: false }"
                        class="p-3 {{ $data->file_path && ($data->video_link || $data->video_path) ? 'col-row-1 md:col-row-2' : '' }} overflow-hidden shadow-lg max-h-fit rounded-xl ring-2 ring-indigo-300 ring-offset-2 ring-offset-indigo-50">
                        {{-- form Upload --}}
                        <p class="mb-3 text-lg font-medium"> Tugas : <br> <span
                                class="text-base font-normal">{{ $data->assigment_content }}</span></p>
                        @if ($assigment)
                            {{-- status section --}}
                            <div x-show="status&&!open">
                                <h3 class="mb-3 font-semibold">Sudah mengumpulkan <span
                                        class="font-bold text-green-500">&check;</span></h3>
                                <div class="grid grid-cols-1">
                                </div>
                                <button @click="window.open('{{ Storage::url($assigment->file_name) }}', '_blank')"
                                    class="p-2 font-semibold text-white transition-colors bg-blue-500 rounded-md hover:bg-blue-700">Lihat
                                    berkas</button>
                                @if (now() <= $data->due_to)
                                    <button @click="open=!open"
                                        class="p-2 font-semibold transition-colors bg-yellow-500 rounded-md hover:text-white hover:bg-yellow-700">Ganti
                                        berkas</button>
                                @endif
                            </div>
                            {{-- form Edit --}}
                            <form x-show="status&&open" action="{{ route('subjectMatterUpdate', $assigment->id) }}"
                                method="POST" enctype="multipart/form-data"
                                class="w-full max-w-md p-6 rounded-lg shadow-md">
                                @csrf
                                @method('PUT')
                                <h1 class="mb-6 text-2xl font-semibold text-center text-gray-800">Ganti PDF File</h1>
                                <label for="pdfFile" class="block mb-2 font-medium text-gray-700">Select PDF
                                    file:</label>
                                <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf" required
                                    class="block w-full mb-6 text-sm text-gray-600 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 file:bg-yellow-600 file:text-white file:border-0 file:rounded-md file:font-semibold file:cursor-pointer file:py-2 file:px-4 hover:file:bg-yellow-700" />
                                <input id="assigment" name="assigment" type="hidden" value="{{ $data->id }}">

                                <div class="grid grid-cols-2 gap-8">

                                    <button type="submit"
                                        class="w-full py-2 font-semibold transition-colors bg-yellow-600 rounded-md hover:text-white hover:bg-yellow-700">
                                        Upload
                                    </button>
                                    <button @click="open=!open"
                                        class="w-full py-2 font-semibold text-white transition-colors bg-red-600 rounded-md hover:bg-red-700">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        @else
                            @if (now() <= $data->due_to)
                                <form x-show="!status&&!open" action="{{ route('subjectMatterUpload') }}"
                                    method="POST" enctype="multipart/form-data"
                                    class="w-full max-w-md p-6 rounded-lg shadow-md">
                                    @csrf
                                    <h1 class="mb-6 text-2xl font-semibold text-center text-gray-800">Unggah PDF File
                                    </h1>
                                    <label for="pdfFile" class="block mb-2 font-medium text-gray-700">Select PDF
                                        file:</label>
                                    <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf"
                                        required
                                        class="block w-full mb-6 text-sm text-gray-600 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:bg-blue-600 file:text-white file:border-0 file:rounded-md file:font-semibold file:cursor-pointer file:py-2 file:px-4 hover:file:bg-blue-700" />
                                    <input id="assigment" name="assigment" type="hidden" value="{{ $data->id }}">

                                    <button type="submit"
                                        class="w-full py-2 font-semibold text-white transition-colors bg-blue-600 rounded-md hover:bg-blue-700">
                                        Upload
                                    </button>
                                </form>
                            @else
                                <p
                                    class="text-2xl font-bold text-center text-red-700 animate__animated animate__flash animate__infinite animate__slow">
                                    Batas waktu terlewat</p>
                            @endif
                        @endif
                        <p class="mt-6 text-base font-normal">Batas pengumpulan :
                            {{ Carbon::parse($data->due_to)->timezone('Asia/Jakarta')->translatedFormat('l, d F Y \p\u\k\u\l H:i') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-front-layout>
