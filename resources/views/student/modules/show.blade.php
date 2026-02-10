@extends('student.layouts.app')

@section('title', $module->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-gray-400 hover:text-[#d4af37] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('student.modules.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Gudang Modul</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium truncate max-w-xs">{{ $module->title }}</span>
    </nav>

    {{-- Module Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden">
        {{-- Header --}}
        <div class="p-6 border-b border-[#1e293b]">
            <div class="flex items-start gap-4">
                <div class="h-12 w-12 rounded-xl bg-[#1e293b] flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $module->class_type == 'reguler' ? 'bg-blue-500/10 text-blue-400' : ($module->class_type == 'karyawan' ? 'bg-purple-500/10 text-purple-400' : 'bg-green-500/10 text-green-400') }}">
                            {{ $module->class_type == 'both' ? 'SEMUA' : strtoupper($module->class_type) }}
                        </span>
                        @if($module->file_type)
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#1e293b] text-gray-400">
                                {{ strtoupper($module->file_type) }}
                            </span>
                        @endif
                        <span class="text-xs text-gray-500">{{ $module->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-white">{{ $module->title }}</h1>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-6">
            @if($module->description)
                <div class="prose prose-invert max-w-none mb-6">
                    <p class="text-gray-300 whitespace-pre-line">{{ $module->description }}</p>
                </div>
            @endif

            {{-- File Download --}}
            @if($module->file_path)
                <div class="bg-[#1e293b]/50 border border-[#1e293b] rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-[#d4af37]/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ $module->original_filename ?? 'File Materi' }}</p>
                                <p class="text-xs text-gray-400">{{ strtoupper($module->file_type ?? 'FILE') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('student.modules.download', $module) }}" 
                            class="inline-flex items-center px-5 py-2.5 rounded-xl bg-[#d4af37] text-[#0b1221] hover:bg-[#b8962e] transition-all text-sm font-bold">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 bg-[#0b1221]/50 border-t border-[#1e293b]">
            <a href="{{ route('student.modules.index') }}" 
                class="inline-flex items-center text-sm text-gray-400 hover:text-[#d4af37] transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Gudang Modul
            </a>
        </div>
    </div>

</div>
@endsection