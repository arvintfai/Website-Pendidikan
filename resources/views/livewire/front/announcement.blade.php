<div>
    @if ($datas != '[]')
        <section wire:poll.60s id="announcement" class="w-full max-w-5xl mx-auto mt-10 rounded-3xl">
            <h2 class="mb-8 text-3xl font-bold text-center text-white">Pengumuman</h2>
            <div class="grid grid-cols-1 gap-8">
                <!-- Announcement Card -->
                @forelse ($datas as $data)
                    <div
                        class="flex flex-col p-6 transition shadow-lg cursor-pointer rounded-xl hover:shadow-2xl bg-indigo-900/10">
                        {{-- <div
                class="flex items-center justify-center h-40 mb-6 text-6xl text-white rounded-lg select-none bg-gradient-to-tr from-pink-400 to-indigo-400">
                🎧</div> --}}
                        <h3 class="mb-2 text-2xl font-semibold text-pink-400">{{ $data->title }}</h3>
                        <div class="flex-grow text-slate-100">{!! str($data->announcement_text)->sanitizeHtml() !!}</div>
                        {{-- <button
                class="px-5 py-3 mt-6 font-semibold text-white transition bg-pink-500 rounded-lg hover:bg-pink-600 focus:outline-none">Buy
                Now</button> --}}
                    </div>
                @empty
                @endforelse
            </div>
        </section>
    @endif
</div>
