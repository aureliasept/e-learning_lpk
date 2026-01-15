@extends('instructor.layouts.app')

@section('title', 'Kelola Quiz')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data>

    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm mb-6">
        <a href="{{ route('instructor.dashboard') }}" class="text-gray-400 hover:text-[#d4af37] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">Kelola Quiz</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Kelola Quiz</h1>
                <p class="text-sm text-gray-400">Buat dan kelola ujian pilihan ganda</p>
            </div>
        </div>
        <a href="{{ route('instructor.quizzes.create') }}" 
            class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            BUAT QUIZ BARU
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="bg-green-900/30 border border-green-500/50 text-green-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="bg-gradient-to-r from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-xl p-4 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <div class="h-8 w-8 rounded-lg bg-[#d4af37]/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-white leading-none">{{ $quizzes->total() }}</p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider">Total Quiz</p>
                    </div>
                </div>
            </div>
            <div class="text-xs text-gray-500">
                Menampilkan {{ $quizzes->firstItem() ?? 0 }} - {{ $quizzes->lastItem() ?? 0 }} dari {{ $quizzes->total() }} quiz
            </div>
        </div>
    </div>

    {{-- Quiz Grid --}}
    @if($quizzes->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($quizzes as $quiz)
                <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden hover:border-[#d4af37]/30 transition-all duration-200">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-white mb-1">{{ $quiz->title }}</h3>
                                @if($quiz->trainingBatch)
                                    <p class="text-xs text-gray-500">{{ $quiz->trainingBatch->name }}</p>
                                @endif
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $quiz->is_active ? 'bg-green-500/10 text-green-400 border border-green-500/30' : 'bg-gray-500/10 text-gray-400 border border-gray-500/30' }}">
                                {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
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
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                KKM: {{ $quiz->passing_score }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-4 border-t border-[#1e293b]">
                            <a href="{{ route('instructor.quizzes.show', $quiz) }}" 
                                class="flex-1 inline-flex justify-center items-center px-4 py-2 rounded-lg bg-[#1e293b] text-gray-300 hover:text-[#d4af37] hover:bg-[#334155] transition-all text-xs font-bold">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                LIHAT
                            </a>
                            <a href="{{ route('instructor.quizzes.edit', $quiz) }}" 
                                class="flex-1 inline-flex justify-center items-center px-4 py-2 rounded-lg bg-[#1e293b] text-gray-300 hover:text-[#d4af37] hover:bg-[#334155] transition-all text-xs font-bold">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                EDIT
                            </a>
                            <button type="button" 
                                @click="$dispatch('confirm-delete', { 
                                    url: '{{ route('instructor.quizzes.destroy', $quiz) }}',
                                    title: 'Hapus Quiz',
                                    message: 'Apakah Anda yakin ingin menghapus quiz {{ $quiz->title }}? Semua soal dan hasil ujian akan ikut terhapus.'
                                })"
                                class="inline-flex justify-center items-center px-3 py-2 rounded-lg bg-[#1e293b] text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $quizzes->links() }}
        </div>
    @else
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-16">
            <div class="flex flex-col items-center justify-center text-center">
                <div class="h-20 w-20 rounded-full bg-[#1e293b] flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Belum ada Quiz</h3>
                <p class="text-gray-400 text-sm mb-6">Buat quiz pertama Anda untuk ujian pilihan ganda.</p>
                <a href="{{ route('instructor.quizzes.create') }}" 
                    class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                    BUAT QUIZ BARU
                </a>
            </div>
        </div>
    @endif
</div>

{{-- Delete Modal --}}
@include('components.delete-modal')
@endsection
