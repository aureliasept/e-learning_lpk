@extends('instructor.layouts.app')

@section('title', 'Kelola Kelas')

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
        <a href="{{ route('instructor.courses.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Gudang Modul</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">{{ Str::limit($course->title ?? $course->name ?? 'Detail', 30) }}</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">{{ $course->title ?? $course->name ?? '-' }}</h1>
                <p class="text-sm text-gray-400 mt-1 max-w-xl">{{ $course->description ?? 'Tidak ada deskripsi.' }}</p>
            </div>
        </div>
        
        <a href="{{ route('instructor.courses.index') }}"
           class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] transition-all duration-200 text-sm font-bold">
            KEMBALI
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-900/30 border border-green-500/50 text-green-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-6 border-b border-[#1e293b]">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span class="text-lg font-bold text-white">Daftar Materi</span>
                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#1e293b] text-gray-400">
                    {{ ($modules ?? collect())->count() }} materi
                </span>
            </div>

            <a href="{{ route('instructor.modules.create', ['course_id' => $course->id]) }}"
               class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] transition-all duration-200 text-xs font-bold">
                + TAMBAH MODUL
            </a>
        </div>

        {{-- Modules List --}}
        <div class="p-6">
            @if(($modules ?? collect())->count() === 0)
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <p class="text-gray-400 font-medium">Belum ada modul untuk kelas ini</p>
                    <p class="text-gray-500 text-sm mt-1">Klik tombol "Tambah Modul" untuk menambah materi</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($modules as $module)
                        <div class="bg-[#0b1221] border border-[#1e293b] rounded-xl p-5 hover:border-[#d4af37]/30 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="h-8 w-8 rounded-lg bg-[#1e293b] border border-[#d4af37]/30 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-[10px] text-[#d4af37] font-bold uppercase tracking-wider">Modul</span>
                                    </div>
                                    <h3 class="text-lg font-bold text-white">{{ $module->title }}</h3>
                                    @if(!empty($module->description))
                                        <p class="text-sm text-gray-400 mt-2">{{ $module->description }}</p>
                                    @endif
                                    @if(!empty($module->file_path))
                                        <div class="mt-3">
                                            <a href="{{ asset('storage/' . $module->file_path) }}" target="_blank"
                                               class="inline-flex items-center gap-2 text-sm text-[#d4af37] hover:underline">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                Download File
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('instructor.modules.edit', $module->id) }}?course_id={{ $course->id }}"
                                       class="inline-flex justify-center items-center px-4 py-2 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] transition-all duration-200 text-xs font-bold">
                                        EDIT
                                    </a>
                                    <button type="button"
                                            @click="$dispatch('confirm-delete', { 
                                                url: '{{ route('instructor.modules.destroy', $module->id) }}',
                                                title: 'Hapus Modul',
                                                message: 'Apakah Anda yakin ingin menghapus modul \'{{ $module->title }}\'? Tindakan ini tidak dapat dibatalkan.'
                                            })"
                                            class="inline-flex justify-center items-center px-4 py-2 rounded-xl border border-red-500/50 text-red-400 hover:bg-red-500/10 hover:border-red-500 transition-all duration-200 text-xs font-bold">
                                        HAPUS
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
