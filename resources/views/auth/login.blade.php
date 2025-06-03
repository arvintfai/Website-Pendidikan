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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label class="text-white" for="email" :value="__('Username')" />
                <x-text-input id="email" class="block w-full mt-1" type="text" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label class="text-white" for="password" :value="__('Password')" />

                <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required
                    autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center text-white">
                    <input id="remember_me" type="checkbox"
                        class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                        name="remember">
                    <span class="text-sm text-gray-600 ms-2 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                {{-- @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif --}}

                <x-primary-button class="text-white bg-pink-500 hover:bg-pink-700 ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>


</x-front-layout>
