{{-- 
    Komponen Modal Peringatan/Alert yang Reusable
    Digunakan untuk validasi client-side dan notifikasi
    
    Contoh Penggunaan:
    <x-alert-modal 
        id="password-warning"
        type="warning"
        title="Peringatan Password"
        message="Password harus minimal 8 karakter!"
    />
    
    Untuk trigger modal, gunakan:
    $dispatch('open-alert-modal', { id: 'password-warning', title: 'Judul', message: 'Pesan' })
--}}

@props([
    'id' => 'alert-modal',
    'type' => 'warning', // warning, error, success, info
    'title' => 'Peringatan',
    'message' => ''
])

@php
$iconColors = [
    'warning' => 'text-[#d4af37]',
    'error' => 'text-red-400',
    'success' => 'text-green-400',
    'info' => 'text-blue-400',
];

$bgColors = [
    'warning' => 'bg-[#d4af37]/10 border-[#d4af37]/30',
    'error' => 'bg-red-500/10 border-red-500/30',
    'success' => 'bg-green-500/10 border-green-500/30',
    'info' => 'bg-blue-500/10 border-blue-500/30',
];

$buttonColors = [
    'warning' => 'from-[#d4af37] to-[#b8962e] shadow-[#d4af37]/25 text-[#0b1221]',
    'error' => 'from-red-600 to-red-500 shadow-red-500/25 text-white',
    'success' => 'from-green-600 to-green-500 shadow-green-500/25 text-white',
    'info' => 'from-blue-600 to-blue-500 shadow-blue-500/25 text-white',
];

$glowColors = [
    'warning' => 'bg-[#d4af37]/20',
    'error' => 'bg-red-500/20',
    'success' => 'bg-green-500/20',
    'info' => 'bg-blue-500/20',
];
@endphp

<div 
    x-data="{ 
        open: false, 
        modalTitle: '{{ $title }}', 
        modalMessage: '{{ $message }}',
        modalType: '{{ $type }}'
    }"
    x-show="open"
    x-on:open-alert-modal.window="
        if ($event.detail.id === '{{ $id }}') {
            modalTitle = $event.detail.title || '{{ $title }}';
            modalMessage = $event.detail.message || '{{ $message }}';
            modalType = $event.detail.type || '{{ $type }}';
            open = true;
        }
    "
    x-on:keydown.escape.window="open = false"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="alert-modal-title-{{ $id }}"
    role="dialog"
    aria-modal="true"
>
    {{-- Backdrop dengan animasi --}}
    <div 
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"
        @click="open = false"
    ></div>

    {{-- Modal Container --}}
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            {{-- Modal Panel dengan animasi --}}
            <div 
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-gradient-to-b from-[#1e293b] to-[#0f172a] border border-[#334155] shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md"
            >
                {{-- Glow Effect --}}
                <div class="absolute -top-24 -left-24 w-48 h-48 {{ $glowColors[$type] }} rounded-full blur-3xl"></div>
                
                <div class="relative p-6">
                    {{-- Icon --}}
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full {{ $bgColors[$type] }} border-2 mb-5">
                        @if($type === 'warning')
                        <svg class="h-8 w-8 {{ $iconColors[$type] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                        @elseif($type === 'error')
                        <svg class="h-8 w-8 {{ $iconColors[$type] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @elseif($type === 'success')
                        <svg class="h-8 w-8 {{ $iconColors[$type] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @else
                        <svg class="h-8 w-8 {{ $iconColors[$type] }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        @endif
                    </div>
                    
                    {{-- Judul --}}
                    <h3 class="text-xl font-bold text-white text-center mb-3" id="alert-modal-title-{{ $id }}" x-text="modalTitle">
                        {{ $title }}
                    </h3>
                    
                    {{-- Pesan --}}
                    <p class="text-gray-400 text-center text-sm leading-relaxed mb-6" x-text="modalMessage">
                        {{ $message }}
                    </p>
                    
                    {{-- Tombol OK --}}
                    <button 
                        type="button" 
                        @click="open = false"
                        class="w-full inline-flex justify-center items-center px-4 py-3 rounded-xl bg-gradient-to-r {{ $buttonColors[$type] }} font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#0f172a] shadow-lg transition-all duration-200 hover:opacity-90"
                    >
                        Mengerti
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
