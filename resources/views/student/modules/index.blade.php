@extends('student.layouts.app')

@section('title', 'Gudang Modul')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
        <span class="text-[#d4af37] font-medium">Gudang Modul</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Gudang Modul</h1>
                <p class="text-sm text-gray-400">Materi pembelajaran dari instruktur</p>
            </div>
        </div>
    </div>

    {{-- Info Card --}}
    @if($student && $student->trainingYear)
    <div class="bg-gradient-to-r from-[#d4af37]/10 to-transparent border border-[#d4af37]/30 rounded-xl p-4 mb-6">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-gray-300">
                Menampilkan materi untuk: 
                <span class="text-[#d4af37] font-bold">{{ $student->trainingYear->name ?? '-' }}</span>
                <span class="ml-2 px-2 py-0.5 text-xs font-bold rounded-full {{ $student->type == 'reguler' ? 'bg-blue-500/20 text-blue-400' : 'bg-purple-500/20 text-purple-400' }}">
                    {{ strtoupper($student->type) }}
                </span>
            </div>
        </div>
    </div>
    @endif



    {{-- Modules Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($modules as $module)
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden hover:shadow-lg hover:shadow-[#d4af37]/5 transition-all duration-300 group">
                {{-- Header --}}
                <div class="p-5 border-b border-[#1e293b]">
                    <div class="flex items-start justify-between gap-3">
                        <div class="h-10 w-10 rounded-xl bg-[#1e293b] flex items-center justify-center flex-shrink-0 group-hover:bg-[#d4af37]/10 transition-colors">
                            @if($module->file_type)
                                @php
                                    $iconColor = match(strtolower($module->file_type)) {
                                        'pdf' => 'text-red-400',
                                        'doc', 'docx' => 'text-blue-400',
                                        'ppt', 'pptx' => 'text-orange-400',
                                        'xls', 'xlsx' => 'text-green-400',
                                        'jpg', 'jpeg', 'png' => 'text-purple-400',
                                        default => 'text-[#d4af37]'
                                    };
                                @endphp
                                <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $module->class_type == 'reguler' ? 'bg-blue-500/10 text-blue-400' : ($module->class_type == 'karyawan' ? 'bg-purple-500/10 text-purple-400' : 'bg-green-500/10 text-green-400') }}">
                                    {{ $module->class_type == 'both' ? 'SEMUA' : strtoupper($module->class_type) }}
                                </span>
                                @if($module->file_type)
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#1e293b] text-gray-400">
                                        {{ strtoupper($module->file_type) }}
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-white truncate group-hover:text-[#d4af37] transition-colors">
                                {{ $module->title }}
                            </h3>
                        </div>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-5">
                    @if($module->description)
                        <p class="text-sm text-gray-400 line-clamp-3 mb-4">
                            {{ Str::limit($module->description, 120) }}
                        </p>
                    @else
                        <p class="text-sm text-gray-500 italic mb-4">Tidak ada deskripsi</p>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">
                            {{ $module->created_at->diffForHumans() }}
                        </span>
                        
                        @if($module->file_path)
                            <a href="{{ route('student.modules.download', $module) }}" 
                                class="inline-flex items-center px-4 py-2 rounded-lg bg-[#d4af37]/10 text-[#d4af37] hover:bg-[#d4af37] hover:text-[#0b1221] transition-all text-sm font-bold">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download
                            </a>
                        @else
                            <span class="text-xs text-gray-500 italic">Tidak ada file</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-12 text-center">
                <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Belum Ada Materi</h3>
                <p class="text-gray-400">Instruktur belum menambahkan materi untuk tahun pelatihan dan kelas Anda.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($modules->hasPages())
        <div class="mt-8">
            {{ $modules->links() }}
        </div>
    @endif

</div>
@endsection
