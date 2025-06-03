@push('styles')
    <style>
        @keyframes glowShadow {

            0%,
            100% {
                filter: drop-shadow(0 0 3px white);
            }

            50% {
                filter: drop-shadow(0 0 25px white);
            }
        }

        .animate__glow {
            animation: glowShadow 5s ease-in-out infinite;
        }
    </style>
@endpush
<x-front-layout>
    <div
        class="w-full max-w-xl px-6 py-4 mx-auto mt-32 overflow-hidden shadow-md backdrop:blur-md mb-14 bg-indigo-900/50 dark:bg-gray-800 sm:rounded-lg">
        <a href="/">
            {{-- <x-application-logo class="w-20 h-20 text-gray-500 fill-current" /> --}}
            <img src="{{ asset('icon-dkv.png') }}" alt="icon website"
                class="w-28 h-[7.5rem] mx-auto text-gray-500 fill-current mt-4 mb-14 animate__glow">
        </a>
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required
                    autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block w-full mt-1" type="password"
                    name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-front-layout>
