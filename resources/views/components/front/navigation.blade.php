<nav x-data="{ navOpen: false }" @click.outside="navOpen = false"
    class="fixed top-0 left-0 z-20 w-full p-6 bg-indigo-900/50 backdrop-blur-sm">
    <div class="flex items-center justify-between mx-auto max-w-7xl">
        <a href="#" class="text-2xl font-bold tracking-wide text-white select-none">Desain Komunikasi
            Visual</a>
        <div class="flex space-x-8 font-semibold text-white md:flex">
            {{-- <a href="#features" class="transition hover:text-pink-400">Features</a>
            <a href="#about" class="transition hover:text-pink-400">About</a>
            <a href="#contact" class="transition hover:text-pink-400">Contact</a> --}}
            <!-- User login display and dropdown -->
            @auth
                <div x-data="{ open: false }" class="relative">
                    <!-- User Info -->
                    <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                        <img src="https://ui-avatars.com/api/?name={{ substr(strtoupper(auth()->user()->name), 0, 1) }}&color=FFFFFF&background=09090b"
                            alt="User Avatar" class="w-10 h-10 border rounded-full">
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 w-48 p-2 rounded-lg shadow-lg mt-7 bg-indigo-900/50 backdrop-blur-sm">
                        {{-- <a href="#"
                            class="block px-4 py-2 text-gray-700 bg-indigo-900/80 hover:bg-gray-100">Profile</a>
                        <a href="#"
                            class="block px-4 py-2 text-gray-700 bg-indigo-900/80 hover:bg-gray-100">Settings</a> --}}
                        <button @click="window.location.href='{{ route('profile.edit') }}'"
                            class="flex w-full gap-3 px-3 py-2 mb-2 rounded-full bg-indigo-900/80">
                            <img src="https://ui-avatars.com/api/?name={{ substr(strtoupper(auth()->user()->name), 0, 1) }}&color=FFFFFF&background=09090b"
                                alt="User Avatar" class="w-10 h-10 border rounded-full">
                            <span class="self-center hidden font-semibold md:block">{{ auth()->user()->name }}</span>
                        </button>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button @click="open = false"
                                class="block w-full px-4 py-2 text-left rounded-sm hover:bg-indigo-900/80 hover:bg-gray-100">
                                <span class="flex gap-2">
                                    <svg class="w-5 h-5 text-gray-400 fi-dropdown-list-item-icon dark:text-gray-500"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true" data-slot="icon">
                                        <path fill-rule="evenodd"
                                            d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z"
                                            clip-rule="evenodd"></path>
                                        <path fill-rule="evenodd"
                                            d="M19 10a.75.75 0 0 0-.75-.75H8.704l1.048-.943a.75.75 0 1 0-1.004-1.114l-2.5 2.25a.75.75 0 0 0 0 1.114l2.5 2.25a.75.75 0 1 0 1.004-1.114l-1.048-.943h9.546A.75.75 0 0 0 19 10Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Log out
                                </span></button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
        {{-- <button @click="navOpen = !navOpen" class="text-white md:hidden focus:outline-none" aria-label="Toggle menu">
            <svg x-show="!navOpen" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
            </svg>
            <svg x-show="navOpen" xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button> --}}
    </div>
    {{-- <!-- Mobile Menu -->
    <div x-show="navOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="p-4 mt-4 space-y-3 rounded-lg md:hidden bg-indigo-900/80 backdrop-blur-sm">
        <a href="#features" class="block font-semibold text-white transition hover:text-pink-400"
            @click="navOpen = false">Features</a>
        <a href="#about" class="block font-semibold text-white transition hover:text-pink-400"
            @click="navOpen = false">About</a>
        <a href="#contact" class="block font-semibold text-white transition hover:text-pink-400"
            @click="navOpen = false">Contact</a>
        <a href="#" class="block font-semibold text-white transition hover:text-pink-400"
            @click="navOpen = false">Logout</a>
    </div> --}}
</nav>
