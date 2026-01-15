@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#d4af37] text-sm font-semibold leading-5 text-[#d4af37] focus:outline-none transition duration-150 ease-in-out h-full'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-[#d4af37] hover:border-[#d4af37]/30 focus:outline-none transition duration-150 ease-in-out h-full';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>