<x-filament-panels::page>
    <div class="py-5">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
            <div
                class="p-4 bg-white shadow sm:p-8 dark:bg-{{ Auth::user()->hasRole('administrator') ? 'gray-900' : 'zinc-900' }} sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.avatar-upload')
                </div>
            </div>

            <div
                class="p-4 bg-white shadow sm:p-8 dark:bg-{{ Auth::user()->hasRole('administrator') ? 'gray-900' : 'zinc-900' }} sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div
                class="p-4 bg-white shadow sm:p-8 dark:bg-{{ Auth::user()->hasRole('administrator') ? 'gray-900' : 'zinc-900' }} sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.verify-email-button')
                </div>
            </div>

            <div
                class="p-4 bg-white shadow sm:p-8 dark:bg-{{ Auth::user()->hasRole('administrator') ? 'gray-900' : 'zinc-900' }} sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div
                class="p-4 bg-white shadow sm:p-8 dark:bg-{{ Auth::user()->hasRole('administrator') ? 'gray-900' : 'zinc-900' }} sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
