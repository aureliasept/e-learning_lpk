@extends('student.layouts.app')

@section('title', 'Verifikasi Kode Akses')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
            {{-- Header --}}
            <div class="p-8 text-center border-b border-[#1e293b]">
                <div class="h-20 w-20 rounded-full bg-[#d4af37]/10 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Masukkan Kode Akses</h1>
                <p class="text-gray-400 text-sm">Quiz ini memerlukan kode akses dari instruktur</p>
            </div>

            {{-- Quiz Info --}}
            <div class="px-8 pt-6">
                <div class="bg-[#0b1221] border border-[#1e293b] rounded-xl p-4">
                    <h3 class="text-lg font-bold text-white mb-2">{{ $quiz->title }}</h3>
                    <div class="flex items-center gap-4 text-xs text-gray-500">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $quiz->questions->count() }} Soal
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $quiz->duration_minutes }} Menit
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('student.exam.verify.submit', $quiz) }}" method="POST" class="p-8" x-data="codeInput()">
                @csrf
                
                @if($errors->any())
                    <div class="bg-red-900/30 border border-red-500/50 text-red-300 p-4 rounded-xl mb-6 flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ $errors->first() }}</span>
                    </div>
                @endif

                <input type="hidden" name="access_code" x-model="fullCode">

                {{-- Code Input Boxes --}}
                <div class="flex justify-center gap-3 mb-8">
                    <template x-for="(digit, index) in digits" :key="index">
                        <input type="text" 
                            maxlength="1" 
                            x-model="digits[index]"
                            @input="handleInput(index, $event)"
                            @keydown.backspace="handleBackspace(index, $event)"
                            @paste="handlePaste($event)"
                            :x-ref="'digit' + index"
                            class="w-12 h-14 text-center text-2xl font-bold bg-[#0b1221] border-2 rounded-xl text-white focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all uppercase"
                            :class="digits[index] ? 'border-[#d4af37]' : 'border-[#1e293b]'"
                            autocomplete="off">
                    </template>
                </div>

                <button type="submit" 
                    :disabled="!isComplete"
                    :class="isComplete ? 'bg-[#d4af37] hover:bg-[#b8962e]' : 'bg-[#1e293b] cursor-not-allowed'"
                    class="w-full inline-flex justify-center items-center px-6 py-4 rounded-xl text-[#0b1221] transition-all text-sm font-bold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    VERIFIKASI & MULAI UJIAN
                </button>

                <p class="text-center text-gray-500 text-sm mt-6">
                    Minta kode akses kepada instruktur Anda
                </p>
            </form>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('student.exam.index') }}" class="text-[#d4af37] hover:underline text-sm">
                ← Kembali ke Daftar Quiz
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function codeInput() {
    return {
        digits: ['', '', '', '', '', ''],
        
        get fullCode() {
            return this.digits.join('');
        },
        
        get isComplete() {
            return this.digits.every(d => d !== '');
        },
        
        handleInput(index, event) {
            const value = event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            this.digits[index] = value;
            
            if (value && index < 5) {
                this.$nextTick(() => {
                    const nextInput = document.querySelector(`[x-ref="digit${index + 1}"]`);
                    if (nextInput) nextInput.focus();
                });
            }
        },
        
        handleBackspace(index, event) {
            if (!this.digits[index] && index > 0) {
                const prevInput = document.querySelector(`[x-ref="digit${index - 1}"]`);
                if (prevInput) {
                    prevInput.focus();
                    this.digits[index - 1] = '';
                }
            }
        },
        
        handlePaste(event) {
            event.preventDefault();
            const pastedData = (event.clipboardData || window.clipboardData)
                .getData('text')
                .toUpperCase()
                .replace(/[^A-Z0-9]/g, '')
                .slice(0, 6);
            
            for (let i = 0; i < 6; i++) {
                this.digits[i] = pastedData[i] || '';
            }
        }
    }
}
</script>
@endpush
@endsection
