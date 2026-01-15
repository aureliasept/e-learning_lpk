@extends('instructor.layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ showQuestions: false, activeTab: 'analytics' }">

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
        <a href="{{ route('instructor.quizzes.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Kelola Quiz</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">{{ Str::limit($quiz->title, 30) }}</span>
    </nav>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="bg-green-900/30 border border-green-500/50 text-green-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Hero Header Card --}}
    <div class="bg-gradient-to-br from-[#0f172a] via-[#1e293b]/50 to-[#0f172a] border border-[#1e293b] rounded-2xl overflow-hidden mb-8">
        <div class="p-6 md:p-8">
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
                {{-- Quiz Info --}}
                <div class="flex items-start gap-4">
                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20 flex-shrink-0">
                        <svg class="w-8 h-8 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white tracking-wide mb-2">{{ $quiz->title }}</h1>
                        <div class="flex flex-wrap items-center gap-2">
                            {{-- Status Badge --}}
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold {{ $quiz->is_active ? 'bg-green-500/10 text-green-400 border border-green-500/30' : 'bg-gray-500/10 text-gray-400 border border-gray-500/30' }}">
                                <span class="w-2 h-2 rounded-full {{ $quiz->is_active ? 'bg-green-400' : 'bg-gray-400' }} mr-2 animate-pulse"></span>
                                {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            @if($quiz->access_code)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-purple-500/10 text-purple-400 border border-purple-500/30">
                                    🔐 Token: <span class="ml-1 font-mono tracking-widest">{{ $quiz->access_code }}</span>
                                </span>
                            @endif
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-[#d4af37]/10 text-[#d4af37] border border-[#d4af37]/30">
                                ⏱️ {{ $quiz->duration_minutes }} Menit
                            </span>
                        </div>
                        @if($quiz->description)
                            <p class="text-gray-400 text-sm mt-3 max-w-xl">{{ $quiz->description }}</p>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('instructor.quizzes.edit', $quiz) }}" 
                        class="inline-flex items-center px-5 py-2.5 rounded-xl bg-[#1e293b] text-gray-300 hover:text-[#d4af37] hover:bg-[#334155] transition-all text-sm font-bold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Quiz
                    </a>
                    <form action="{{ route('instructor.quizzes.toggle_active', $quiz) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-xl border {{ $quiz->is_active ? 'border-red-500/50 text-red-400 hover:bg-red-500/10' : 'border-green-500/50 text-green-400 hover:bg-green-500/10' }} transition-all text-sm font-bold">
                            @if($quiz->is_active)
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Nonaktifkan
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Aktifkan
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Quick Stats Bar --}}
        <div class="grid grid-cols-2 md:grid-cols-4 border-t border-[#1e293b]">
            <div class="p-5 text-center border-r border-[#1e293b] last:border-r-0">
                <p class="text-3xl font-bold text-[#d4af37]">{{ $quiz->questions->count() }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Soal</p>
            </div>
            <div class="p-5 text-center border-r border-[#1e293b] last:border-r-0">
                <p class="text-3xl font-bold text-white">{{ $analytics['total_attempts'] }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Peserta</p>
            </div>
            <div class="p-5 text-center border-r border-[#1e293b] last:border-r-0">
                <p class="text-3xl font-bold text-white">{{ $analytics['average_score'] }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Rata-rata</p>
            </div>
            <div class="p-5 text-center">
                <p class="text-3xl font-bold {{ $analytics['pass_rate'] >= 70 ? 'text-green-400' : ($analytics['pass_rate'] >= 50 ? 'text-yellow-400' : 'text-red-400') }}">{{ $analytics['pass_rate'] }}%</p>
                <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Lulus</p>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        {{-- Sidebar Info --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Quiz Details Card --}}
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden">
                <div class="p-4 border-b border-[#1e293b] bg-[#1e293b]/30">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Detail Quiz
                    </h3>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-[#1e293b]/50">
                        <span class="text-gray-500">KKM</span>
                        <span class="text-white font-bold px-2 py-0.5 rounded bg-[#1e293b]">{{ $quiz->passing_score }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-[#1e293b]/50">
                        <span class="text-gray-500">Gelombang</span>
                        <span class="text-white font-medium">{{ $quiz->trainingBatch->name ?? 'Semua' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-[#1e293b]/50">
                        <span class="text-gray-500">Dibuat</span>
                        <span class="text-gray-400 text-xs">{{ $quiz->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Settings Card --}}
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden">
                <div class="p-4 border-b border-[#1e293b] bg-[#1e293b]/30">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Pengaturan
                    </h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Acak Soal</span>
                        <span class="w-6 h-6 rounded-full flex items-center justify-center {{ $quiz->shuffle_questions ? 'bg-green-500/20 text-green-400' : 'bg-gray-600/20 text-gray-500' }}">
                            @if($quiz->shuffle_questions)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Acak Pilihan</span>
                        <span class="w-6 h-6 rounded-full flex items-center justify-center {{ $quiz->shuffle_options ? 'bg-green-500/20 text-green-400' : 'bg-gray-600/20 text-gray-500' }}">
                            @if($quiz->shuffle_options)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Tampil Pembahasan</span>
                        <span class="w-6 h-6 rounded-full flex items-center justify-center {{ $quiz->show_answers_after ? 'bg-green-500/20 text-green-400' : 'bg-gray-600/20 text-gray-500' }}">
                            @if($quiz->show_answers_after)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Kode Akses</span>
                        <span class="w-6 h-6 rounded-full flex items-center justify-center {{ $quiz->access_code ? 'bg-purple-500/20 text-purple-400' : 'bg-gray-600/20 text-gray-500' }}">
                            @if($quiz->access_code)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content Area --}}
        <div class="lg:col-span-3 space-y-6">
            {{-- Tabs --}}
            <div class="flex gap-2 border-b border-[#1e293b] pb-4">
                <button @click="activeTab = 'analytics'" 
                    :class="activeTab === 'analytics' ? 'bg-[#d4af37] text-[#0b1221]' : 'bg-[#1e293b] text-gray-400 hover:text-white'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                    Analisis Butir Soal
                </button>
                <button @click="activeTab = 'participants'" 
                    :class="activeTab === 'participants' ? 'bg-[#d4af37] text-[#0b1221]' : 'bg-[#1e293b] text-gray-400 hover:text-white'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                    Peserta ({{ $quiz->attempts->count() }})
                </button>
                <button @click="activeTab = 'questions'" 
                    :class="activeTab === 'questions' ? 'bg-[#d4af37] text-[#0b1221]' : 'bg-[#1e293b] text-gray-400 hover:text-white'"
                    class="px-4 py-2 rounded-lg text-sm font-bold transition-all">
                    Bank Soal ({{ $quiz->questions->count() }})
                </button>
            </div>

            {{-- Tab: Analytics --}}
            <div x-show="activeTab === 'analytics'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @if($analytics['total_attempts'] > 0)
                <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden">
                    <div class="p-5 border-b border-[#1e293b] bg-[#1e293b]/30">
                        <h3 class="text-base font-bold text-white">Statistik Kesulitan Soal</h3>
                        <p class="text-xs text-gray-400 mt-1">Berdasarkan {{ $analytics['total_attempts'] }} jawaban peserta</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs text-gray-500 uppercase tracking-wider bg-[#0b1221]">
                                    <th class="px-5 py-4 font-bold">No</th>
                                    <th class="px-5 py-4 font-bold">Pertanyaan</th>
                                    <th class="px-5 py-4 font-bold text-center">✓ Benar</th>
                                    <th class="px-5 py-4 font-bold text-center">✗ Salah</th>
                                    <th class="px-5 py-4 font-bold text-center">Persentase</th>
                                    <th class="px-5 py-4 font-bold text-center">Tingkat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#1e293b]">
                                @foreach($analytics['questions'] as $qStat)
                                <tr class="text-sm hover:bg-[#1e293b]/30 transition-colors">
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#d4af37]/10 text-[#d4af37] font-bold text-sm">{{ $qStat['order'] }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-gray-300 max-w-xs">
                                        <p class="truncate">{{ Str::limit($qStat['question_text'], 50) }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="text-green-400 font-bold">{{ $qStat['correct_count'] }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="text-red-400 font-bold">{{ $qStat['incorrect_count'] }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-center gap-3">
                                            <div class="w-20 bg-[#1e293b] rounded-full h-2.5 overflow-hidden">
                                                <div class="h-full rounded-full transition-all {{ $qStat['correct_percentage'] >= 70 ? 'bg-gradient-to-r from-green-500 to-green-400' : ($qStat['correct_percentage'] >= 50 ? 'bg-gradient-to-r from-yellow-500 to-yellow-400' : 'bg-gradient-to-r from-red-500 to-red-400') }}" 
                                                    style="width: {{ $qStat['correct_percentage'] }}%"></div>
                                            </div>
                                            <span class="text-white font-bold text-sm w-12">{{ $qStat['correct_percentage'] }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                            {{ $qStat['difficulty'] === 'Sangat Sulit' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                                            {{ $qStat['difficulty'] === 'Sulit' ? 'bg-orange-500/10 text-orange-400 border border-orange-500/20' : '' }}
                                            {{ $qStat['difficulty'] === 'Sedang' ? 'bg-yellow-500/10 text-yellow-400 border border-yellow-500/20' : '' }}
                                            {{ $qStat['difficulty'] === 'Mudah' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : '' }}
                                            {{ $qStat['difficulty'] === 'Sangat Mudah' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}">
                                            {{ $qStat['difficulty'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-12 text-center">
                    <div class="h-20 w-20 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h4 class="text-white font-bold text-lg mb-2">Belum Ada Data Analisis</h4>
                    <p class="text-gray-400">Statistik akan muncul setelah ada peserta yang mengerjakan quiz</p>
                </div>
                @endif
            </div>

            {{-- Tab: Participants --}}
            <div x-show="activeTab === 'participants'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden">
                    @if($quiz->attempts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs text-gray-500 uppercase tracking-wider bg-[#0b1221]">
                                    <th class="px-6 py-4 font-bold">Peserta</th>
                                    <th class="px-6 py-4 font-bold text-center">Nilai</th>
                                    <th class="px-6 py-4 font-bold text-center">Benar/Total</th>
                                    <th class="px-6 py-4 font-bold text-center">Status</th>
                                    <th class="px-6 py-4 font-bold">Waktu Selesai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#1e293b]">
                                @foreach($quiz->attempts->sortByDesc('finished_at') as $attempt)
                                <tr class="text-sm hover:bg-[#1e293b]/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-[#d4af37]/20 to-[#d4af37]/5 flex items-center justify-center border border-[#d4af37]/20">
                                                <span class="text-[#d4af37] text-sm font-bold">{{ strtoupper(substr($attempt->student->user->name ?? 'U', 0, 2)) }}</span>
                                            </div>
                                            <div>
                                                <span class="text-white font-medium block">{{ $attempt->student->user->name ?? 'Unknown' }}</span>
                                                <span class="text-gray-500 text-xs">{{ $attempt->student->user->email ?? '' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($attempt->status === 'completed')
                                            <span class="text-3xl font-bold {{ $attempt->isPassed() ? 'text-green-400' : 'text-red-400' }}">{{ $attempt->score }}</span>
                                        @else
                                            <span class="text-gray-500 text-xl">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($attempt->status === 'completed')
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-[#1e293b] text-white text-sm font-mono">
                                                <span class="text-green-400">{{ $attempt->total_correct }}</span>
                                                <span class="text-gray-500 mx-1">/</span>
                                                <span>{{ $quiz->questions->count() }}</span>
                                            </span>
                                        @else
                                            <span class="text-gray-500">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($attempt->status === 'completed')
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold {{ $attempt->isPassed() ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                                {{ $attempt->isPassed() ? '✓ LULUS' : '✗ TIDAK LULUS' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 animate-pulse">
                                                ⏳ BERLANGSUNG
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-400 text-sm">
                                        {{ $attempt->finished_at ? $attempt->finished_at->format('d M Y, H:i') : 'Belum selesai' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="p-12 text-center">
                        <div class="h-20 w-20 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-white font-bold text-lg mb-2">Belum Ada Peserta</h4>
                        <p class="text-gray-400">Quiz ini belum dikerjakan oleh peserta manapun</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Tab: Questions --}}
            <div x-show="activeTab === 'questions'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="space-y-4">
                    @forelse($quiz->questions as $index => $question)
                    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden hover:border-[#d4af37]/30 transition-colors">
                        <div class="p-5">
                            {{-- Question Header --}}
                            <div class="flex items-start gap-4">
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] text-[#0b1221] text-sm font-bold flex-shrink-0 shadow-lg shadow-[#d4af37]/10">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    {{-- Question Text --}}
                                    <p class="text-white text-base leading-relaxed mb-4">{{ $question->question_text }}</p>
                                    
                                    {{-- Media Section (Image & Audio) --}}
                                    @if($question->image_url || $question->audio_url)
                                    <div class="flex flex-wrap gap-4 mb-4 p-4 bg-[#0b1221] rounded-xl border border-[#1e293b]">
                                        @if($question->image_url)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $question->image_url) }}" 
                                                alt="Gambar Soal {{ $index + 1 }}"
                                                class="max-h-40 rounded-lg border border-[#1e293b] object-contain bg-[#0b1221] cursor-pointer hover:border-[#d4af37]/50 transition-colors"
                                                onclick="window.open(this.src, '_blank')">
                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                </svg>
                                            </div>
                                            <span class="absolute bottom-2 left-2 px-2 py-1 bg-black/70 rounded text-xs text-white">📷 Gambar</span>
                                        </div>
                                        @endif
                                        @if($question->audio_url)
                                        <div class="flex-1 min-w-[200px]">
                                            <p class="text-xs text-gray-500 mb-2 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                                </svg>
                                                Audio / Listening
                                            </p>
                                            <audio controls class="w-full h-10 rounded-lg">
                                                <source src="{{ asset('storage/' . $question->audio_url) }}" type="audio/mpeg">
                                                Browser tidak mendukung audio.
                                            </audio>
                                        </div>
                                        @endif
                                    </div>
                                    @endif

                                    {{-- Options --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($question->options as $optIndex => $option)
                                        <div class="flex items-center gap-3 p-3 rounded-xl transition-colors {{ $option->is_correct ? 'bg-green-500/10 border border-green-500/30' : 'bg-[#0b1221] border border-[#1e293b]' }}">
                                            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold {{ $option->is_correct ? 'bg-green-500/20 text-green-400' : 'bg-[#1e293b] text-gray-400' }}">
                                                {{ chr(65 + $optIndex) }}
                                            </span>
                                            <span class="flex-1 text-sm {{ $option->is_correct ? 'text-green-400 font-medium' : 'text-gray-300' }}">
                                                {{ $option->option_text }}
                                            </span>
                                            @if($option->is_correct)
                                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>

                                    {{-- Explanation --}}
                                    @if($question->explanation)
                                    <div class="mt-4 p-4 bg-[#d4af37]/5 border border-[#d4af37]/20 rounded-xl">
                                        <div class="flex items-start gap-2">
                                            <span class="text-[#d4af37] flex-shrink-0">💡</span>
                                            <div>
                                                <p class="text-[#d4af37] text-xs font-bold uppercase tracking-wider mb-1">Pembahasan</p>
                                                <p class="text-gray-300 text-sm">{{ $question->explanation }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-12 text-center">
                        <div class="h-20 w-20 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-white font-bold text-lg mb-2">Belum Ada Soal</h4>
                        <p class="text-gray-400 mb-4">Quiz ini belum memiliki soal</p>
                        <a href="{{ route('instructor.quizzes.edit', $quiz) }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-[#d4af37] text-[#0b1221] font-bold text-sm hover:bg-[#b8962e] transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Soal
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
