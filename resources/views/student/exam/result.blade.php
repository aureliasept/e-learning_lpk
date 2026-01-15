@extends('student.layouts.app')

@section('title', 'Hasil Ujian')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Result Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="p-8 text-center border-b border-[#1e293b]">
            @if($attempt->isPassed())
                <div class="h-24 w-24 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-green-400 mb-2">SELAMAT, ANDA LULUS!</h1>
                <p class="text-gray-400">Anda telah berhasil menyelesaikan ujian dengan nilai memuaskan.</p>
            @else
                <div class="h-24 w-24 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-red-400 mb-2">BELUM LULUS</h1>
                <p class="text-gray-400">Nilai Anda belum memenuhi batas kelulusan. Tetap semangat!</p>
            @endif
        </div>
        
        {{-- Score Display --}}
        <div class="p-8">
            <div class="grid grid-cols-3 gap-6 mb-8">
                <div class="bg-[#0b1221] border border-[#1e293b] rounded-xl p-6 text-center">
                    <p class="text-4xl font-bold {{ $attempt->isPassed() ? 'text-green-400' : 'text-red-400' }}">{{ $attempt->score }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mt-2">Nilai Anda</p>
                </div>
                <div class="bg-[#0b1221] border border-[#1e293b] rounded-xl p-6 text-center">
                    <p class="text-4xl font-bold text-[#d4af37]">{{ $attempt->quiz->passing_score }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mt-2">Nilai KKM</p>
                </div>
                <div class="bg-[#0b1221] border border-[#1e293b] rounded-xl p-6 text-center">
                    <p class="text-4xl font-bold text-white">{{ $attempt->total_correct }}/{{ $attempt->quiz->questions->count() }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mt-2">Jawaban Benar</p>
                </div>
            </div>
            
            {{-- Quiz Info --}}
            <div class="bg-[#0b1221] border border-[#1e293b] rounded-xl p-6 mb-8">
                <h3 class="text-lg font-bold text-white mb-4">{{ $attempt->quiz->title }}</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Waktu Mulai:</span>
                        <span class="text-white ml-2">{{ $attempt->started_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Waktu Selesai:</span>
                        <span class="text-white ml-2">{{ $attempt->finished_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Durasi Pengerjaan:</span>
                        <span class="text-white ml-2">{{ $attempt->started_at->diffForHumans($attempt->finished_at, true) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Status:</span>
                        <span class="ml-2 px-2 py-1 rounded-lg text-xs font-bold {{ $attempt->isPassed() ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                            {{ $attempt->isPassed() ? 'LULUS' : 'TIDAK LULUS' }}
                        </span>
                    </div>
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex justify-center gap-4">
                <a href="{{ route('student.exam.index') }}" 
                    class="inline-flex items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] transition-all text-sm font-bold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    KEMBALI KE DAFTAR QUIZ
                </a>
            </div>
        </div>
    </div>

    {{-- Answer Review Section --}}
    @if($attempt->quiz->show_answers_after)
    <div class="mt-8 bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden" x-data="{ showReview: false }">
        <div class="p-6 border-b border-[#1e293b] flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-white">📖 Pembahasan Jawaban</h3>
                <p class="text-sm text-gray-400">Lihat kunci jawaban dan penjelasan untuk setiap soal</p>
            </div>
            <button @click="showReview = !showReview" 
                class="inline-flex items-center px-4 py-2 rounded-xl bg-[#1e293b] text-gray-300 hover:text-[#d4af37] hover:bg-[#334155] transition-all text-sm font-bold">
                <span x-text="showReview ? 'Sembunyikan' : 'Tampilkan Pembahasan'"></span>
                <svg class="w-4 h-4 ml-2 transition-transform" :class="showReview ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
        
        <div x-show="showReview" x-collapse class="p-6 space-y-6">
            @php
                $answers = $attempt->answers ?? [];
            @endphp
            
            @foreach($attempt->quiz->questions as $index => $question)
                @php
                    $correctOption = $question->options->where('is_correct', true)->first();
                    $studentAnswerId = $answers[$question->id] ?? null;
                    $studentAnswer = $question->options->where('id', $studentAnswerId)->first();
                    $isCorrect = $correctOption && $studentAnswerId == $correctOption->id;
                @endphp
                
                <div class="bg-[#0b1221] border rounded-xl p-6 {{ $isCorrect ? 'border-green-500/30' : 'border-red-500/30' }}">
                    {{-- Question Header --}}
                    <div class="flex items-start gap-3 mb-4">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg text-sm font-bold flex-shrink-0 {{ $isCorrect ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                @if($isCorrect)
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold bg-green-500/10 text-green-400">
                                        ✓ BENAR
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-bold bg-red-500/10 text-red-400">
                                        ✗ SALAH
                                    </span>
                                @endif
                            </div>
                            <p class="text-white">{{ $question->question_text }}</p>
                            
                            @if($question->audio_url)
                                <div class="mt-3">
                                    <audio controls class="h-8 w-full max-w-sm">
                                        <source src="{{ Storage::url($question->audio_url) }}" type="audio/mpeg">
                                    </audio>
                                </div>
                            @endif
                            
                            @if($question->image_url)
                                <img src="{{ Storage::url($question->image_url) }}" class="mt-3 max-h-32 rounded-lg">
                            @endif
                        </div>
                    </div>
                    
                    {{-- Options --}}
                    <div class="ml-11 space-y-2 mb-4">
                        @foreach($question->options as $optIndex => $option)
                            @php
                                $isStudentAnswer = $studentAnswerId == $option->id;
                                $isCorrectOption = $option->is_correct;
                            @endphp
                            <div class="flex items-center gap-3 p-3 rounded-lg text-sm
                                {{ $isCorrectOption ? 'bg-green-500/10 border border-green-500/30' : '' }}
                                {{ $isStudentAnswer && !$isCorrectOption ? 'bg-red-500/10 border border-red-500/30' : '' }}
                                {{ !$isStudentAnswer && !$isCorrectOption ? 'bg-[#1e293b]/50' : '' }}">
                                <span class="w-6 h-6 rounded flex items-center justify-center text-xs font-bold
                                    {{ $isCorrectOption ? 'bg-green-500/20 text-green-400' : '' }}
                                    {{ $isStudentAnswer && !$isCorrectOption ? 'bg-red-500/20 text-red-400' : '' }}
                                    {{ !$isStudentAnswer && !$isCorrectOption ? 'bg-[#1e293b] text-gray-400' : '' }}">
                                    {{ chr(65 + $optIndex) }}
                                </span>
                                <span class="{{ $isCorrectOption ? 'text-green-400' : ($isStudentAnswer ? 'text-red-400' : 'text-gray-400') }}">
                                    {{ $option->option_text }}
                                </span>
                                @if($isCorrectOption)
                                    <svg class="w-4 h-4 text-green-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @endif
                                @if($isStudentAnswer && !$isCorrectOption)
                                    <svg class="w-4 h-4 text-red-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Answer Summary --}}
                    <div class="ml-11 text-sm space-y-1">
                        <p class="text-gray-400">
                            <span class="text-gray-500">Jawaban Anda:</span> 
                            <span class="{{ $isCorrect ? 'text-green-400' : 'text-red-400' }}">
                                {{ $studentAnswer ? chr(65 + $question->options->search(fn($o) => $o->id == $studentAnswer->id)) . '. ' . $studentAnswer->option_text : 'Tidak dijawab' }}
                            </span>
                        </p>
                        @if(!$isCorrect && $correctOption)
                        <p class="text-gray-400">
                            <span class="text-gray-500">Jawaban Benar:</span> 
                            <span class="text-green-400">
                                {{ chr(65 + $question->options->search(fn($o) => $o->id == $correctOption->id)) }}. {{ $correctOption->option_text }}
                            </span>
                        </p>
                        @endif
                    </div>
                    
                    {{-- Explanation --}}
                    @if($question->explanation)
                        <div class="ml-11 mt-4 p-4 bg-[#1e293b]/50 rounded-lg">
                            <p class="text-sm text-gray-400">
                                <span class="text-[#d4af37] font-bold">💡 Pembahasan:</span><br>
                                {{ $question->explanation }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="mt-8 bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-8 text-center">
        <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <p class="text-gray-400">Pembahasan jawaban tidak tersedia untuk quiz ini.</p>
        <p class="text-sm text-gray-500 mt-2">Hubungi instruktur jika Anda memerlukan penjelasan lebih lanjut.</p>
    </div>
    @endif
</div>
@endsection
