{{-- 
    KOMPONEN GLOBAL DELETE MODAL
    
    Pendekatan: Single Modal + Dynamic Action URL
    
    Modal ini diletakkan SEKALI di layout, lalu tombol hapus mengirimkan
    URL delete melalui event Alpine.js.
    
    Cara Pakai di Tabel:
    <button type="button" 
        x-data
        @click="$dispatch('confirm-delete', { 
            url: '{{ route('admin.students.destroy', $student->id) }}',
            title: 'Hapus Siswa',
            message: 'Apakah Anda yakin ingin menghapus siswa ini?'
        })">
        Hapus
    </button>
--}}

<div 
    x-data="globalDeleteModal()"
    x-show="open"
    x-on:confirm-delete.window="openModal($event.detail)"
    x-on:keydown.escape.window="open = false"
    x-cloak
    class="fixed inset-0 z-[9999] overflow-y-auto"
    aria-labelledby="delete-modal-title"
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
        <div class="flex min-h-full items-start justify-center p-4 pt-[120px] sm:pt-[140px]">
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
                @click.away="open = false"
            >
                {{-- Glow Effect --}}
                <div class="absolute -top-24 -left-24 w-48 h-48 bg-red-500/20 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-[#d4af37]/10 rounded-full blur-3xl"></div>
                
                <div class="relative p-6">
                    {{-- Icon Peringatan --}}
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-red-500/10 border-2 border-red-500/30 mb-5">
                        <svg class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    
                    {{-- Judul (Dinamis) --}}
                    <h3 class="text-xl font-bold text-white text-center mb-3" id="delete-modal-title" x-text="title">
                        Konfirmasi Hapus
                    </h3>
                    
                    {{-- Pesan (Dinamis) --}}
                    <p class="text-gray-400 text-center text-sm leading-relaxed mb-6" x-text="message">
                        Apakah Anda yakin ingin menghapus data ini?
                    </p>
                    
                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col-reverse sm:flex-row gap-3">
                        {{-- Tombol Batal --}}
                        <button 
                            type="button" 
                            @click="open = false"
                            class="flex-1 inline-flex justify-center items-center px-4 py-3 rounded-xl bg-[#1e293b] border border-[#334155] text-gray-300 font-semibold text-sm hover:bg-[#334155] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#334155] focus:ring-offset-2 focus:ring-offset-[#0f172a] transition-all duration-200"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </button>
                        
                        {{-- Form Hapus dengan Action Dinamis --}}
                        <form :action="deleteUrl" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-500 text-white font-semibold text-sm hover:from-red-500 hover:to-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-[#0f172a] shadow-lg shadow-red-500/25 transition-all duration-200"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Ya, Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function globalDeleteModal() {
        return {
            open: false,
            deleteUrl: '',
            title: 'Konfirmasi Hapus',
            message: 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
            
            openModal(detail) {
                this.deleteUrl = detail.url || '';
                this.title = detail.title || 'Konfirmasi Hapus';
                this.message = detail.message || 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.';
                this.open = true;
            }
        }
    }
</script>
