<a
    href="{{ $href }}"
    title="{{ $title }}"
    target="{{ $target }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center
                    w-8 h-8 rounded-md hover:bg-gray-100'
    ]) }}
>
    {{ $slot }}
</a>