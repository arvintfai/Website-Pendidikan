<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Status Email') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Check your verification status email address.') }}
        </p>
    </header>

    @if (!Auth::user()->hasVerifiedEmail())
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">
                <span style="--c-50:var(--danger-50);--c-400:var(--danger-400);--c-600:var(--danger-600);"
                    class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-danger">
                    Verifikasi Email
                </span>
            </button>
        </form>
    @else
        <span style="--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600); width: max-content;"
            class="fi-badge flex items-center justify-center gap-x-1 rounded-md text-xs font-medium ring-1 ring-inset px-2 min-w-[theme(spacing.6)] py-1 fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success">
            Sudah Terverifikasi
        </span>
    @endif
</section>
