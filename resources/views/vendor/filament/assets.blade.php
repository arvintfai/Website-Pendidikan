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

{{-- <link rel="stylesheet" href="{{ asset('build/assets/app-Pp1hITZz.css') }}"> --}}

<link rel="icon" type="image/png" href="{{ asset('icon-dkv.png') }}">

<style>
    :root {
        @foreach ($cssVariables ?? [] as $cssVariableName => $cssVariableValue)
            --{{ $cssVariableName }}: {{ $cssVariableValue }};
        @endforeach
    }
</style>
