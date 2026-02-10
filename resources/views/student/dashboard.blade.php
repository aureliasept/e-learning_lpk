@extends('student.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Welcome Section --}}
    <div class="bg-gradient-to-r from-[#0f172a] to-[#1e293b] border border-[#1e293b] rounded-2xl p-8 mb-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-[#d4af37]/5 to-transparent"></div>
        <div class="relative">
            <h1 class="text-2xl font-bold text-white mb-2">Selamat datang, {{ $user->name }}! 👋</h1>
            <p class="text-gray-400">
                @if($student && $student->trainingYear)
                    {{ $student->trainingYear->name }} - {{ $student->classroom ?? '' }}
                @else
                    Belum terdaftar di tahun pelatihan
                @endif
            </p>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        {{-- Pending Tasks --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-orange-500/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $pendingTasks }}</p>
                    <p class="text-sm text-gray-400">Tugas Pending</p>
                </div>
            </div>
        </div>

        {{-- Available Quizzes --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-500/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $availableQuizzes }}</p>
                    <p class="text-sm text-gray-400">Quiz Tersedia</p>
                </div>
            </div>
        </div>

        {{-- Year Info --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-[#d4af37]/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold text-white">{{ $student?->trainingYear?->name ?? '-' }}</p>
                    <p class="text-sm text-gray-400">Tahun Pelatihan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Instructions --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-[#1e293b] flex items-center justify-between">
                <h2 class="text-lg font-bold text-white">Instruksi Terbaru</h2>
                <a href="{{ route('student.instructions.index') }}" class="text-sm text-[#d4af37] hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-[#1e293b]">
                @forelse($recentInstructions->take(4) as $instruction)
                    @php
                        $submission = $instruction->submissions->first();
                        $hasSubmitted = $submission !== null;
                    @endphp
                    <a href="{{ route('student.instructions.show', $instruction) }}" class="block p-4 hover:bg-[#1e293b]/30 transition">
                        <div class="flex items-start gap-3">
                            <div class="h-8 w-8 rounded-lg {{ $instruction->is_task ? 'bg-orange-500/10' : 'bg-[#1e293b]' }} flex items-center justify-center flex-shrink-0">
                                @if($instruction->is_task)
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="text-white font-semibold text-sm truncate">{{ $instruction->title }}</p>
                                    @if($instruction->is_task && $hasSubmitted)
                                        <span class="px-1.5 py-0.5 text-[10px] font-bold rounded bg-green-500/10 text-green-400">✓</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500">{{ $instruction->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center">
                        <p class="text-gray-500 text-sm">Belum ada instruksi</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- News --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-[#1e293b] flex items-center justify-between">
                <h2 class="text-lg font-bold text-white">Berita Terbaru</h2>
                <a href="{{ route('student.news.index') }}" class="text-sm text-[#d4af37] hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-[#1e293b]">
                @forelse($news as $item)
                    <a href="{{ route('student.news.show', $item->slug) }}" class="block p-4 hover:bg-[#1e293b]/30 transition">
                        <div class="flex gap-4">
                            <div class="w-20 h-14 bg-[#1e293b] rounded-lg overflow-hidden flex-shrink-0">
                                @if($item->image)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[#d4af37] text-xs font-bold">GBI</div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-white font-semibold text-sm line-clamp-2">{{ $item->title }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ ($item->published_at ?? $item->created_at)?->format('d M Y') }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center">
                        <p class="text-gray-500 text-sm">Belum ada berita</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection