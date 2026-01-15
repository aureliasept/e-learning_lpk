@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-3 border-l-4 border-[#c9a341] text-start text-xs font-bold uppercase tracking-widest text-white bg-[#0f172a] focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-xs font-bold uppercase tracking-widest text-gray-300 hover:text-[#c9a341] hover:bg-[#0f172a] focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
