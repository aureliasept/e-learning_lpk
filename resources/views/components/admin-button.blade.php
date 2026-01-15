{{--
    Reusable Admin Button Components
    
    Contoh Penggunaan:
    <x-admin-button href="{{ route('admin.users.create') }}">
        <x-slot name="icon">
            <svg>...</svg>
        </x-slot>
        TAMBAH DATA
    </x-admin-button>
    
    <x-admin-button type="submit" variant="danger">
        HAPUS
    </x-admin-button>
--}}

@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, ghost
    'size' => 'md' // sm, md, lg
])

@php
$baseClasses = 'inline-flex items-center justify-center gap-2 font-bold rounded-xl transition-all duration-200';

$variants = [
    'primary' => 'bg-gradient-to-r from-[#d4af37] to-[#b8962e] hover:from-[#e5c349] hover:to-[#d4af37] text-[#0b1221] shadow-lg shadow-[#d4af37]/20',
    'secondary' => 'bg-[#1e293b] border border-[#334155] text-gray-300 hover:text-white hover:bg-[#334155] hover:border-[#475569]',
    'danger' => 'bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 text-white shadow-lg shadow-red-500/20',
    'ghost' => 'bg-transparent text-gray-400 hover:text-[#d4af37] hover:bg-[#1e293b]',
];

$sizes = [
    'sm' => 'py-2 px-4 text-xs',
    'md' => 'py-2.5 px-6 text-sm',
    'lg' => 'py-3 px-8 text-base',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($icon))
        {{ $icon }}
    @endif
    {{ $slot }}
</a>
@else
<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($icon))
        {{ $icon }}
    @endif
    {{ $slot }}
</button>
@endif
