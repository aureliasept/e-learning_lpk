{{--
    Reusable Admin Alert Component
    
    Contoh Penggunaan:
    <x-admin-alert type="success">
        Data berhasil disimpan!
    </x-admin-alert>
    
    <x-admin-alert type="error" dismissible>
        Terjadi kesalahan!
    </x-admin-alert>
--}}

@props([
    'type' => 'info', // success, error, warning, info
    'dismissible' => false
])

@php
$styles = [
    'success' => [
        'bg' => 'bg-green-900/30 border-green-500/50',
        'text' => 'text-green-300',
        'icon' => 'text-green-400',
        'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
    ],
    'error' => [
        'bg' => 'bg-red-900/30 border-red-500/50',
        'text' => 'text-red-300',
        'icon' => 'text-red-400',
        'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
    ],
    'warning' => [
        'bg' => 'bg-yellow-900/30 border-yellow-500/50',
        'text' => 'text-yellow-300',
        'icon' => 'text-yellow-400',
        'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
    ],
    'info' => [
        'bg' => 'bg-blue-900/30 border-blue-500/50',
        'text' => 'text-blue-300',
        'icon' => 'text-blue-400',
        'iconPath' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
    ],
];

$style = $styles[$type];
@endphp

<div 
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="{{ $style['bg'] }} {{ $style['text'] }} border p-4 rounded-xl mb-6 flex items-center gap-3"
>
    <svg class="w-5 h-5 {{ $style['icon'] }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $style['iconPath'] !!}
    </svg>
    <span class="text-sm font-medium flex-grow">{{ $slot }}</span>
    
    @if($dismissible)
    <button @click="show = false" class="flex-shrink-0 {{ $style['icon'] }} hover:opacity-70 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    @endif
</div>
