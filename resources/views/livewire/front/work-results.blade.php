@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp
<section wire:poll.visible.5000ms id="works"
    class="w-full max-w-6xl px-6 py-16 mx-auto my-10 mt-16 shadow-xl bg-white/90 rounded-3xl">
    <h2 class="text-3xl font-bold text-center text-indigo-900 mb-14">Hasil Karya Siswa</h2>
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3">
        <!-- Product Card 1 -->
        @forelse ($work_results as $index => $work_result)
            <article
                class="flex flex-col overflow-hidden transition-shadow duration-300 bg-white rounded-lg shadow-md hover:shadow-lg">
                <img src={{ $work_result->photo_path }} alt="Karya {{ $index + 1 }}" class="object-cover w-full h-48">
                <div class="flex flex-col flex-grow p-6">
                    <h3 class="mb-2 text-2xl font-semibold cursor-pointer hover:text-indigo-600">
                        {{ $work_result->title }}</h3>
                    <p class="mb-4 text-sm text-gray-500">Oleh {{ $work_result->User->name }} &bull;
                        {{ $work_result->created_at->diffForHumans() }}</p>
                    {!! Str::limit($work_result->paragraph, 100) !!}
                    <a href="{{ route('workShow', $work_result->slug) }}"
                        class="inline-block mt-auto font-semibold text-indigo-600 hover:underline">Lihat lebih lengkap
                        &rarr;</a>
                </div>
            </article>
        @empty
            <div class="relative col-span-3 mx-auto my-3 text-center">
                <h3 class="mb-3 text-3xl font-extrabold">&#9747;</h3>
                <p class="font-base">Belum ada karya</p>
            </div>
        @endforelse
    </div>
    @if (count($work_results) > 0)
        <div class="mt-8 text-center">
            <a href="{{ route('works') }}" class="text-xl font-semibold text-indigo-900 hover:underline">Lihat
                lebih
                banyak
                &raquo;</a>
        </div>
    @endif
</section>
