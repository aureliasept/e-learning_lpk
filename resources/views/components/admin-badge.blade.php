{{--
    Reusable Admin Badge Component
    
    Contoh Penggunaan:
    <x-admin-badge type="success" pulse>Aktif</x-admin-badge>
    <x-admin-badge type="secondary">Periode Lampau</x-admin-badge>
    <x-admin-badge type="gold">Kelas A</x-admin-badge>
--}}

@props([
    'type' => 'default', // success, warning, danger, info, secondary, gold, default
    'pulse' => false,
    'size' => 'sm' // sm, md
])

@php
$styles = [
    'success' => 'bg-green-500/10 text-green-400 border-green-500/30',
    'warning' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/30',
    'danger' => 'bg-red-500/10 text-red-400 border-red-500/30',
    'info' => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
    'secondary' => 'bg-gray-500/10 text-gray-400 border-gray-500/30',
    'gold' => 'bg-[#1e293b] text-[#d4af37] border-[#d4af37]/30',
    'default' => 'bg-[#1e293b] text-white border-[#334155]',
];

$pulseColors = [
    'success' => 'bg-green-400',
    'warning' => 'bg-yellow-400',
    'danger' => 'bg-red-400',
    'info' => 'bg-blue-400',
    'secondary' => 'bg-gray-500',
    'gold' => 'bg-[#d4af37]',
    'default' => 'bg-white',
];

$sizes = [
    'sm' => 'px-2.5 py-1 text-xs',
    'md' => 'px-3 py-1.5 text-sm',
];

$style = $styles[$type] ?? $styles['default'];
$pulseColor = $pulseColors[$type] ?? $pulseColors['default'];
$sizeClass = $sizes[$size] ?? $sizes['sm'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full border font-semibold {$style} {$sizeClass}"]) }}>
    @if($pulse)
    <span class="w-1.5 h-1.5 rounded-full {{ $pulseColor }} animate-pulse"></span>
    @endif
    {{ $slot }}
</span>
