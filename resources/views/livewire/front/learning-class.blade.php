{{-- {{ dump($subjectMatters) }} --}}
@php
    use Carbon\Carbon;

    Carbon::setLocale('id');
@endphp
<section wire:poll.visible.5000ms id="classes"
    class="w-full max-w-5xl px-6 py-16 mx-auto mt-10 shadow-xl bg-white/90 rounded-3xl">
    <h2 class="mb-6 text-3xl font-bold text-center text-indigo-900">Materi Kelas Anda</h2>
    <div class="flex px-6 pb-3 -mx-6 space-x-6 overflow-x-auto no-scrollbar">
        <!-- Card -->
        @forelse ($subjectMatters as $subjectMatter)
            <div
                class="flex flex-col p-6 transition shadow-lg cursor-pointer shrink-0 w-72 bg-indigo-50 rounded-xl hover:shadow-2xl">
                {{-- {{ auth()->user()->wasOpened->contains($subjectMatter->id) ? 'true' : 'false' }} --}}
                <div class="mb-4 text-5xl text-pink-500 select-none">
                    @if (auth()->user()->wasOpened->contains($subjectMatter->id))
                        @if ($subjectMatter->is_has_assigment)
                            @if ($subjectMatter->assigment == '[]')
                                @if (now() <= $subjectMatter->due_to)
                                    <div class="relative text-right max-h-1">
                                        <p class="text-2xl font-extrabold">⌚
                                        </p>
                                    </div>
                                @else
                                    <div class="relative text-right max-h-1">
                                        <p class="text-2xl font-semibold">❌
                                        </p>
                                    </div>
                                @endif
                            @else
                                <div class="relative text-right max-h-1">
                                    <p class="text-2xl font-extrabold ">✅
                                    </p>
                                </div>
                            @endif
                        @else
                            <div class="relative text-right max-h-1">
                                <p class="text-2xl font-extrabold ">✅
                                </p>
                            </div>
                        @endif
                    @elseif (
                        !auth()->user()->wasOpened->contains($subjectMatter->id) &&
                            $subjectMatter->assigment == '[]' &&
                            $subjectMatter->is_has_assigment)
                        <div class="relative text-right max-h-1">
                            <p class="text-2xl font-extrabold text-red-600">&excl;
                            </p>
                        </div>
                    @endif
                    @if (($subjectMatter->video_link || $subjectMatter->video_path) && $subjectMatter->file_path)
                        📄▶️
                    @elseif ($subjectMatter->video_link || $subjectMatter->video_path)
                        ▶️
                    @else
                        📄
                    @endif

                </div>
                <h3 class="mb-2 text-xl font-semibold text-indigo-900">{{ $subjectMatter->name }}</h3>
                @if ($subjectMatter->is_has_assigment)
                    <p class="flex-grow text-indigo-700">{{ Str::limit($subjectMatter->assigment_content, 55) }}</p>
                    <p
                        class="flex-grow text-xs {{ now() <= $subjectMatter->due_to ? 'text-gray-700' : 'text-red-700' }}">
                        Batas Pengumpulan :
                        {{ Carbon::parse($subjectMatter->due_to)->timezone('Asia/Jakarta')->translatedFormat('l, d F Y \p\u\k\u\l H:i') }}
                        WIB</p>
                @else
                    <p class="flex-grow"></p>
                    <p class="flex-grow"></p>
                @endif
                <a href="{{ route('subjectMatterView', Str::lower(Str::replace(' ', '-', $subjectMatter->name))) }}"
                    class="self-start px-4 py-2 mt-4 font-semibold text-white transition bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none">Lihat
                    Materi</a>
            </div>
        @empty
            <div class="relative mx-auto my-3 text-center">
                <h3 class="mb-3 text-3xl font-extrabold">&#9747;</h3>
                <p class="font-base">Belum ada materi</p>
            </div>
        @endforelse

    </div>
    @if (count($subjectMatters) > 0)
        {{ $subjectMatters->links() }}
    @endif
</section>
