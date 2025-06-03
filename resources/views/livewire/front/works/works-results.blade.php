@php
    use Carbon\Carbon;

    Carbon::setLocale('id');
@endphp
<section wire:poll.visible.5000ms id="works"
    class="max-w-6xl px-6 py-16 mx-auto my-10 mt-32 shadow-xl bg-white/90 rounded-3xl">
    <h2 class="text-3xl font-bold text-center text-indigo-900 mb-14">Hasil Karya Siswa</h2>
    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3">
        <!-- Product Card 1 -->
        @forelse ($works as $index => $work)
            <article
                class="flex flex-col overflow-hidden transition-shadow duration-300 bg-white rounded-lg shadow-md hover:shadow-lg">
                <img src="{{ $work->photo_path ?? 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80' }}"
                    alt="Karya {{ $index + 1 }}" class="object-cover w-full h-48">
                <div class="flex flex-col flex-grow p-6">
                    <h3 class="mb-2 text-2xl font-semibold cursor-pointer hover:text-indigo-600">{{ $work->title }}
                    </h3>
                    <p class="mb-4 text-sm text-gray-500">Oleh {{ $work->User->name }} &bull;
                        {{ $work->created_at->diffForHumans() }}</p>
                    {!! Str::limit($work->paragraph, 100) !!}
                    <a href="{{ route('workShow', $work->slug) }}"
                        class="inline-block mt-auto font-semibold text-indigo-600 hover:underline">Read More
                        &rarr;</a>
                </div>
            </article>
        @empty
        @endforelse
    </div>
</section>
