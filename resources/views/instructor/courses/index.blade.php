@extends('instructor.layouts.app')

@section('title', 'Modul')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
        <span class="text-[#d4af37] font-medium">Modul</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Modul</h1>
                <p class="text-sm text-gray-400">Daftar modul yang Anda ampu</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-900/30 border border-green-500/50 text-green-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Courses Grid --}}
    @if(($courses ?? collect())->count() === 0)
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl p-12">
            <div class="flex flex-col items-center justify-center text-center">
                <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <p class="text-gray-300 font-bold">Belum ada modul untuk instruktur ini</p>
                <p class="text-gray-500 text-sm mt-2">Hubungi admin untuk menambahkan modul</p>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
                <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-6 shadow-xl hover:border-[#d4af37]/50 transition-colors">
                    <div class="flex items-start justify-between mb-4">
                        <div class="h-12 w-12 rounded-xl bg-[#1e293b] border border-[#d4af37]/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 bg-green-500/10 text-green-400 border border-green-500/30 rounded-full text-xs font-semibold">
                            Aktif
                        </span>
                    </div>

                    <div class="text-[10px] text-[#d4af37] font-bold uppercase tracking-wider mb-1">Modul</div>
                    <h3 class="text-lg font-bold text-white mb-2">
                        {{ $course->title ?? $course->name ?? '-' }}
                    </h3>
                    <p class="text-sm text-gray-400 line-clamp-2 mb-6">
                        {{ $course->description ?? 'Tidak ada deskripsi.' }}
                    </p>

                    <a href="{{ route('instructor.courses.show', $course->id) }}"
                       class="inline-flex justify-center items-center w-full px-4 py-2.5 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] transition-all duration-200 text-sm font-bold">
                        KELOLA MODUL
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
