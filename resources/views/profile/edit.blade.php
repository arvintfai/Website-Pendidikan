<x-front-layout>

    <x-slot name="header">
        <header class="grid items-center grid-cols-2 gap-2 lg:grid-cols-2">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('Profile') }}
            </h2>
            <nav class="flex items-center justify-end">
                <x-theme-button></x-theme-button>
            </nav>
        </header>
    </x-slot>


    <div class="py-12 mt-20 mb-14">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div
                class="p-4 bg-indigo-900/50 backdrop:blur-sm shadow sm:p-8 dark:bg-{{ Auth::user()->hasRole('administrator') ? 'gray-800' : 'zinc-900' }} sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div
                class="p-4 bg-indigo-900/50 backdrop:blur-sm shadow sm:p-8 dark:bg-{{ Auth::user()->hasRole('administrator') ? 'gray-800' : 'zinc-900' }} sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div
                class="p-4 bg-indigo-900/50 backdrop:blur-sm shadow sm:p-8 dark:bg-{{ Auth::user()->hasRole('administrator') ? 'gray-800' : 'zinc-900' }} sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-front-layout>
