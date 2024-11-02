{{-- resources/views/components/show-file.blade.php --}}
@if (isset($file))
    @if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf')
        <iframe src="{{ $file }}" width="100%" height="600px"></iframe>
    @endif
@elseif (isset($video))
    {{-- <div class="flex items-center justify-center"> --}}
    <video controls width="100%" height="auto">
        <source src="{{ asset($video) }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    {{-- </div> --}}
@else
    <img src="{{ $file }}" alt="File" class="w-full h-auto">
@endif
