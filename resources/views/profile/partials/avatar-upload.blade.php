<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Photo Profile') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Update your photo\'s profile') }}
        </p>
    </header>

    <form action="{{ route('profile.uploadAvatar') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            @if (Auth::user()->avatar)
                <img class="object-cover object-center w-32 h-32 mb-4 rounded-full fi-avatar fi-circular"
                    src={{ asset('storage/' . Auth::user()->avatar) }}>
            @endif

            <div class="grid w-full max-w-xs items-center gap-1.5">
                <label for="avatar"
                    class="text-sm font-medium leading-none text-gray-400 peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    {{ Auth::user()->avatar ? 'Change' : 'Choose' }}
                    Photo
                    Profile</label>
                <input name="avatar"
                    class="flex w-full text-sm text-gray-400 bg-white border border-blue-300 rounded-md border-input file:border-0 file:bg-blue-600 file:text-white file:text-sm file:font-medium"
                    type="file" id="avatar" />
            </div>

            @error('avatar')
                <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>



        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Upload Photo Profile') }}</x-primary-button>
    </form>
    @if (Auth::user()->avatar)
        <x-danger-button x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-photo-profil-deletion')">{{ __('Delete Photo Profile') }}</x-danger-button>

        <x-modal name="confirm-photo-profil-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroyAvatar') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to delete your photo profile?') }}
                </h2>

                <div class="flex justify-end mt-6">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        {{ __('Confirm Detele') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endif

    @if (session('status') === 'avatar-updated')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
            class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved') }}</p>
    @elseif(session('status') === 'avatar-deleted')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
            class="text-sm text-gray-600 dark:text-gray-400">{{ __('Deleted') }}</p>
    @endif
    </div>
</section>
