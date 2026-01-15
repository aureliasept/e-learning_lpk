{{-- Table Row Component --}}
@props([])

<tr {{ $attributes->merge(['class' => 'hover:bg-[#1e293b]/40 transition-all duration-200 group']) }}>
    {{ $slot }}
</tr>
