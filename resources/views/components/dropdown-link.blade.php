<a
    {{ $attributes->merge([
        'class' =>
            'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-' .
            (Auth::user()->hasRole('administrator') ? 'gray-100' : 'zinc-200') .
            ' dark:hover:bg-' .
            (Auth::user()->hasRole('administrator') ? 'gray-800' : 'zinc-900') .
            ' focus:outline-none focus:bg-' .
            (Auth::user()->hasRole('administrator') ? 'gray-100' : 'zinc-200') .
            ' dark:focus:bg-' .
            (Auth::user()->hasRole('administrator') ? 'gray-800' : 'zinc-900') .
            ' transition duration-150 ease-in-out',
    ]) }}>
    {{ $slot }}
</a>
