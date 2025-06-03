@props(['title' => 'Modal Title'])
<!-- Modal backdrop -->
<div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" @click.away="open = false"
    style="display: none;">
    <!-- Modal panel -->
    <div x-show="showModal" x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90" @click.stop
        class="w-full max-w-sm p-8 mx-4 bg-white rounded-lg shadow-xl" role="dialog" aria-modal="true"
        aria-labelledby="modal-title" aria-describedby="modal-description">
        <div class="flex items-center justify-between mb-6">
            <h2 id="modal-title" class="text-xl font-semibold text-indigo-900">{{ $title }}</h2>
            <button @click="showModal = false"
                class="text-gray-400 rounded hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                aria-label="Close modal">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        {{ $slot }}
    </div>
</div>
