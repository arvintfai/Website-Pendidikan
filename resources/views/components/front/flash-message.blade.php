@props(['duration' => 7000]) {{-- Durasi default 7000ms (7 detik) --}}

@if (session('success') || session('error') || session('danger'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, {{ $duration }});" x-show="show" x-transition.opacity.duration.300ms
        class="fixed top-24 right-4 z-50 w-full max-w-sm
               p-4 rounded shadow-lg overflow-hidden
               {{ session('success') ? 'bg-green-100 border border-green-400 text-green-700' : '' }}
               {{ session('error') ? 'bg-red-100 border border-red-400 text-red-700' : '' }}
        {{ session('danger') ? 'bg-yellow-100 border border-yellow-400 text-yellow-700' : '' }}">
        <div class="flex items-start justify-between">
            <div class="text-sm font-medium">
                {{ session('success') ?? (session('error') ?? session('danger')) }}
            </div>
            <button @click="show = false" class="ml-4 text-xl font-bold leading-none focus:outline-none">
                &times;
            </button>
        </div>

        {{-- Durasi meter/progress bar --}}
        <div
            class="absolute bottom-0 left-0 w-full h-1 bg-opacity-20
                    {{ (session('success') ? 'bg-green-700' : session('danger')) ? 'bg-yellow-700' : 'bg-red-700' }}">
            <div class="h-full {{ (session('success') ? 'bg-green-500' : session('danger')) ? 'bg-yellow-500' : 'bg-red-500' }}"
                x-init="$el.style.width = '100%';
                setTimeout(() => $el.style.transition = 'width {{ $duration }}ms linear', 10);
                setTimeout(() => $el.style.width = '0%', 20);"></div>
        </div>
    </div>
@endif
