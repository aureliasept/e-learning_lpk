@extends('student.layouts.app')

@section('title', 'Daftar Quiz')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Ujian Online</h1>
                <p class="text-sm text-gray-400">Daftar quiz yang tersedia untuk Anda</p>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('error'))
        <div class="bg-red-900/30 border border-red-500/50 text-red-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('info'))
        <div class="bg-blue-900/30 border border-blue-500/50 text-blue-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('info') }}</span>
        </div>
    @endif

    @if(!$student)
        <div class="bg-yellow-900/30 border border-yellow-500/50 text-yellow-300 p-6 rounded-xl text-center">
            <p class="font-bold">Data peserta belum terhubung</p>
            <p class="text-sm mt-2">Hubungi admin untuk mengatur data peserta Anda.</p>
        </div>
    @elseif($quizzes->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($quizzes as $quiz)
                @php
                    $attempt = $quiz->attempts->first();
                    $isCompleted = $attempt && $attempt->status === 'completed';
                    $isInProgress = $attempt && $attempt->status === 'in_progress';
                @endphp
                <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden hover:border-[#d4af37]/30 transition-all duration-200">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-white mb-1">{{ $quiz->title }}</h3>
                                @if($quiz->trainingBatch)
                                    <p class="text-xs text-gray-500">{{ $quiz->trainingBatch->name }}</p>
                                @endif
                            </div>
                            @if($isCompleted)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $attempt->isPassed() ? 'bg-green-500/10 text-green-400 border border-green-500/30' : 'bg-red-500/10 text-red-400 border border-red-500/30' }}">
                                    {{ $attempt->isPassed() ? 'LULUS' : 'TIDAK LULUS' }}
                                </span>
                            @elseif($isInProgress)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/30">
                                    BERLANGSUNG
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/30">
                                    BARU
                                </span>
                            @endif
                        </div>
                        
                        @if($quiz->description)
                            <p class="text-sm text-gray-400 mb-4 line-clamp-2">{{ $quiz->description }}</p>
                        @endif

                        <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $quiz->question_count }} Soal
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $quiz->duration_minutes }} Menit
                            </div>
                        </div>

                        @if($isCompleted)
                            <div class="bg-[#0b1221] rounded-xl p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400 text-sm">Nilai Anda</span>
                                    <span class="text-2xl font-bold {{ $attempt->isPassed() ? 'text-green-400' : 'text-red-400' }}">{{ $attempt->score }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">KKM: {{ $quiz->passing_score }} | Benar: {{ $attempt->total_correct }}/{{ $quiz->question_count }}</p>
                            </div>
                            <a href="{{ route('student.exam.result', $attempt) }}" 
                                class="w-full inline-flex justify-center items-center px-4 py-3 rounded-xl bg-[#1e293b] text-gray-300 hover:text-[#d4af37] hover:bg-[#334155] transition-all text-sm font-bold">
                                LIHAT HASIL
                            </a>
                        @else
                            <a href="{{ route('student.exam.start', $quiz) }}" 
                                class="w-full inline-flex justify-center items-center px-4 py-3 rounded-xl {{ $isInProgress ? 'bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30' : 'bg-[#d4af37] text-[#0b1221] hover:bg-[#b8962e]' }} transition-all text-sm font-bold">
                                {{ $isInProgress ? 'LANJUTKAN UJIAN' : 'MULAI UJIAN' }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-16">
            <div class="flex flex-col items-center justify-center text-center">
                <div class="h-20 w-20 rounded-full bg-[#1e293b] flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Belum ada Quiz Tersedia</h3>
                <p class="text-gray-400 text-sm">Quiz akan muncul ketika instruktur mempublikasikan ujian untuk gelombang Anda.</p>
            </div>
        </div>
    @endif
</div>
@endsection
