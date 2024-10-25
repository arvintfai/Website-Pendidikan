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

<script src="https://cdn.tailwindcss.com"></script>

<style>
    :root {
        @foreach ($cssVariables ?? [] as $cssVariableName => $cssVariableValue)
            --{{ $cssVariableName }}: {{ $cssVariableValue }};
        @endforeach
    }
</style>
