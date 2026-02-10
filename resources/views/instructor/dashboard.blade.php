@extends('instructor.layouts.app')

@section('title', 'Dashboard')

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
        <span class="text-[#d4af37] font-medium">Dashboard</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Dashboard Instruktur</h1>
                <p class="text-sm text-gray-400">Selamat datang, {{ $user->name }}</p>
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

    {{-- Access Info Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden mb-8">
        <div class="p-6 border-b border-[#1e293b]">
            <h2 class="text-lg font-bold text-white">Akses Kelas Anda</h2>
            <p class="text-sm text-gray-400">Kelas yang dapat Anda kelola</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Reguler Access Card --}}
                <div class="bg-[#0b1221] border {{ $teacher && $teacher->is_reguler ? 'border-blue-500/50' : 'border-[#1e293b]' }} rounded-xl p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="h-10 w-10 rounded-lg {{ $teacher && $teacher->is_reguler ? 'bg-blue-500/20' : 'bg-[#1e293b]' }} flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $teacher && $teacher->is_reguler ? 'text-blue-400' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        @if($teacher && $teacher->is_reguler)
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-blue-500/20 text-blue-400">AKTIF</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#1e293b] text-gray-500">TIDAK AKTIF</span>
                        @endif
                    </div>
                    <h3 class="text-base font-bold {{ $teacher && $teacher->is_reguler ? 'text-white' : 'text-gray-500' }} mb-1">Kelas Reguler</h3>
                    <p class="text-sm {{ $teacher && $teacher->is_reguler ? 'text-gray-400' : 'text-gray-600' }}">
                        {{ $teacher && $teacher->is_reguler ? 'Anda dapat mengelola materi untuk kelas reguler' : 'Tidak memiliki akses ke kelas reguler' }}
                    </p>
                </div>

                {{-- Karyawan Access Card --}}
                <div class="bg-[#0b1221] border {{ $teacher && $teacher->is_karyawan ? 'border-purple-500/50' : 'border-[#1e293b]' }} rounded-xl p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="h-10 w-10 rounded-lg {{ $teacher && $teacher->is_karyawan ? 'bg-purple-500/20' : 'bg-[#1e293b]' }} flex items-center justify-center">
                            <svg class="w-5 h-5 {{ $teacher && $teacher->is_karyawan ? 'text-purple-400' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @if($teacher && $teacher->is_karyawan)
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-purple-500/20 text-purple-400">AKTIF</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#1e293b] text-gray-500">TIDAK AKTIF</span>
                        @endif
                    </div>
                    <h3 class="text-base font-bold {{ $teacher && $teacher->is_karyawan ? 'text-white' : 'text-gray-500' }} mb-1">Kelas Karyawan</h3>
                    <p class="text-sm {{ $teacher && $teacher->is_karyawan ? 'text-gray-400' : 'text-gray-600' }}">
                        {{ $teacher && $teacher->is_karyawan ? 'Anda dapat mengelola materi untuk kelas karyawan' : 'Tidak memiliki akses ke kelas karyawan' }}
                    </p>
                </div>

                {{-- Active Period Card --}}
                <div class="bg-[#0b1221] border border-[#d4af37]/30 rounded-xl p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="h-10 w-10 rounded-lg bg-[#d4af37]/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        @if($activeYear)
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-green-500/20 text-green-400">AKTIF</span>
                        @endif
                    </div>
                    <h3 class="text-base font-bold text-white mb-1">Periode Aktif</h3>
                    <p class="text-sm text-gray-400">
                        @if($activeYear)
                            {{ $activeYear->name }}
                        @else
                            Tidak ada periode aktif
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-8">
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-orange-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $stats['total_instructions'] ?? 0 }}</p>
                    <p class="text-sm text-gray-400">Instruksi Anda</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-[#d4af37]/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $stats['total_modules'] ?? 0 }}</p>
                    <p class="text-sm text-gray-400">Total Materi</p>
                </div>
            </div>
        </div>
    </div>


    {{-- News Section --}}
    @if(($news ?? collect())->count() > 0)
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-[#1e293b]">
            <h2 class="text-lg font-bold text-white">Berita & Informasi</h2>
            <p class="text-sm text-gray-400">Informasi terbaru dari admin</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($news as $item)
                    <a href="{{ route('instructor.news.show', $item->slug) }}" 
                       class="block bg-[#0b1221] border border-[#1e293b] rounded-xl overflow-hidden hover:border-[#d4af37]/50 transition-all hover:shadow-lg hover:shadow-[#d4af37]/5 group">
                        @if($item->image_url)
                            <div class="aspect-video bg-[#1e293b] overflow-hidden">
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="aspect-video bg-[#1e293b] flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <p class="text-[10px] text-[#d4af37] uppercase tracking-wider font-bold mb-1">
                                {{ $item->published_at ? $item->published_at->format('d M Y') : '' }}
                            </p>
                            <h3 class="text-sm font-bold text-white line-clamp-2 mb-2 group-hover:text-[#d4af37] transition-colors">{{ $item->title }}</h3>
                            <p class="text-xs text-gray-400 line-clamp-2">{{ Str::limit(strip_tags($item->content), 80) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>
@endsection