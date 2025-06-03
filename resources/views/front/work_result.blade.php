@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
@endphp
<x-front-layout>
    <section id="work" class="max-w-6xl px-6 py-16 mx-auto my-10 mt-32 shadow-xl bg-white/90 rounded-3xl">
        @if ($data->user_id == Auth::id())
            <div class="flex justify-between pb-6" x-data="{ showDeleteModal: false }">
                <p class="text-xl font-semibold text-indigo-600">Action :</p>
                <button @click="showDeleteModal = !showDeleteModal"
                    class="flex items-center px-5 py-2 font-semibold text-white transition duration-200 bg-red-600 rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-400 active:bg-red-800"
                    aria-label="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
                    </svg>
                    Hapus
                </button>
                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                    @click.away="showDeleteModal = false" style="display: none;">
                    <!-- Modal panel -->
                    <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                        @click.stop class="w-full max-w-sm p-8 mx-4 bg-white rounded-lg shadow-xl" role="dialog"
                        aria-modal="true" aria-labelledby="modal-title" aria-describedby="modal-description">
                        <div class="flex items-center justify-end mb-6">
                            <button @click="showDeleteModal = false"
                                class="text-gray-400 rounded hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                aria-label="Close modal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <h2 class="pb-6 text-2xl font-bold text-center">Yakin ingin menghapus karya ini?</h2>

                        <div class="flex justify-end gap-4">
                            <form action="{{ route('workDestroy', $data->slug) }}" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit"
                                    class="flex items-center px-5 py-2 font-semibold text-white transition duration-200 bg-red-600 rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-400 active:bg-red-800"
                                    aria-label="Hapus">Hapus</button>
                            </form>
                            <button @click="showDeleteModal = !showDeleteModal"
                                class="flex items-center px-5 py-2 font-semibold text-white transition duration-200 rounded-lg shadow-md bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-4 focus:ring-slate-400 active:bg-slate-800"
                                aria-label="Batal">Batal</button>
                        </div>

                    </div>
                </div>
            </div>
        @endif
        <div class="grid grid-cols-1 gap-8">
            <img src="{{ $data->photo_path }}" alt="Foto Karya" class="object-cover max-w-2xl mx-auto">
            @if ($data->video_path)
                <iframe class="w-full h-96" src="{{ $data->video_path }}" title="Video player"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen loading="lazy"></iframe>
            @endif
            <div class="flex flex-col flex-grow p-6">
                <h2 class="text-3xl font-bold">{{ $data->title }}</h2>
                <p class="mb-4 text-sm text-gray-500">By {{ $data->User->name }} &bull;
                    {{ $data->created_at->diffForHumans() }}</p>
                {!! $data->paragraph !!}
            </div>
        </div>
    </section>
</x-front-layout>
