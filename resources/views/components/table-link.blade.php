<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center px-4 py-2
                    border border-black rounded-md font-semibold
                    text-xs text-black uppercase tracking-widest
                    hover:bg-gray-300'
    ]) }}
>
    {{ $slot }}
</a>