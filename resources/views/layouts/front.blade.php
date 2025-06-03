<!DOCTYPE html>
<html lang="en" x-data="app()" x-cloak class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Website Pendidikan - Desain Komunikasi Visual</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Tailwind CSS -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Alpine.js -->
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    @stack('styles')

    @livewireStyles

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</head>

<body class="flex flex-col min-h-screen bg-gradient-to-br from-purple-700 via-indigo-700 to-pink-700">
    <!-- Flash Massage -->
    <x-front.flash-message :duration="5000" />

    <!-- Navigation -->
    <x-front.navigation />

    {{ $slot }}

    <!-- Footer -->
    <x-front.footer />

    @livewireScripts
    <script>
        function app() {
            return {
                showModal: false,
            };
        }
    </script>
    @stack('scripts')
</body>

</html>
</content>
</create_file>
