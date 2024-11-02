@if (isset($data))
    <script>
        window.filamentData = @js($data)
    </script>
@endif

@foreach ($assets as $asset)
    @if (!$asset->isLoadedOnRequest())
        {{ $asset->getHtml() }}
    @endif
@endforeach

@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite('resources/css/app.css')
@endif
{{-- <script src="https://cdn.tailwindcss.com"></script> --}}
{{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

{{-- <link rel="stylesheet" href="{{ mix('/resource/css/app.css') }}"> --}}
<style>
    :root {
        @foreach ($cssVariables ?? [] as $cssVariableName => $cssVariableValue)
            --{{ $cssVariableName }}: {{ $cssVariableValue }};
        @endforeach
    }
</style>
