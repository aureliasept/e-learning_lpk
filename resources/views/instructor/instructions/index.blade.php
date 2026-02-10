@extends('instructor.layouts.app')

@section('title', 'Papan Instruksi')

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
        <span class="text-[#d4af37] font-medium">Papan Instruksi</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Papan Instruksi</h1>
                <p class="text-sm text-gray-400">Kelola materi & tugas untuk siswa</p>
            </div>
        </div>
        <a href="{{ route('instructor.instructions.create', ['year' => $selectedYearId]) }}" 
            class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            BUAT INSTRUKSI
        </a>
    </div>

    {{-- Access Info --}}
    <div class="bg-[#1e293b]/50 border border-[#1e293b] rounded-xl p-4 mb-6 flex items-center gap-3">
        <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm text-gray-300">
            Akses Anda:
            @if($canTeachReguler && $canTeachKaryawan)
                <span class="text-[#d4af37] font-bold">Reguler & Karyawan</span>
            @elseif($canTeachReguler)
                <span class="text-[#d4af37] font-bold">Reguler</span>
            @elseif($canTeachKaryawan)
                <span class="text-[#d4af37] font-bold">Karyawan</span>
            @else
                <span class="text-red-400 font-bold">Tidak ada akses kelas</span>
            @endif
        </span>
    </div>

    @if(session('success'))
        <div class="bg-green-900/30 border border-green-500/50 text-green-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-6 mb-6">
        <form method="GET" action="{{ route('instructor.instructions.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Year Filter --}}
            <div>
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Tahun Pelatihan</label>
                <select name="year" onchange="this.form.submit()"
                    class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37]">
                    @foreach($trainingYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Class Type Filter --}}
            <div>
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Tipe Kelas</label>
                <select name="class_type" onchange="this.form.submit()"
                    class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37]">
                    <option value="all" {{ $selectedClassType == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="reguler" {{ $selectedClassType == 'reguler' ? 'selected' : '' }}>Reguler</option>
                    <option value="karyawan" {{ $selectedClassType == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                </select>
            </div>

            {{-- Stats --}}
            <div class="flex items-end">
                <div class="w-full bg-[#1e293b]/50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-[#d4af37]">{{ $instructions->total() }}</p>
                    <p class="text-xs text-gray-400">Total Instruksi</p>
                </div>
            </div>
        </form>
    </div>

    {{-- Timeline --}}
    <div class="space-y-4">
        @forelse($instructions as $instruction)
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border {{ $instruction->is_task ? 'border-orange-500/30' : 'border-[#1e293b]' }} rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            {{-- Icon --}}
                            <div class="h-10 w-10 rounded-xl {{ $instruction->is_task ? 'bg-orange-500/10' : 'bg-[#1e293b]' }} flex items-center justify-center flex-shrink-0">
                                @if($instruction->is_task)
                                    <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    @if($instruction->is_task)
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-orange-500/10 text-orange-400">TUGAS</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#1e293b] text-gray-400">MATERI</span>
                                    @endif
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $instruction->class_type == 'reguler' ? 'bg-blue-500/10 text-blue-400' : ($instruction->class_type == 'karyawan' ? 'bg-purple-500/10 text-purple-400' : 'bg-green-500/10 text-green-400') }}">
                                        {{ strtoupper($instruction->class_type) }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $instruction->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <h3 class="text-lg font-bold text-white mb-2">{{ $instruction->title }}</h3>
                                
                                @if($instruction->description)
                                    <p class="text-sm text-gray-400 line-clamp-2">{{ Str::limit(strip_tags($instruction->description), 150) }}</p>
                                @endif

                                <div class="flex flex-wrap items-center gap-3 mt-3">
                                    @if($instruction->file_path)
                                        <span class="inline-flex items-center text-xs text-gray-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            Lampiran
                                        </span>
                                    @endif
                                    
                                    @if($instruction->is_task && $instruction->deadline)
                                        <span class="inline-flex items-center text-xs {{ $instruction->isDeadlinePassed() ? 'text-red-400' : 'text-orange-400' }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $instruction->deadline->format('d M Y, H:i') }}
                                        </span>
                                    @endif
                                    
                                    @if($instruction->is_task)
                                        <span class="inline-flex items-center text-xs text-[#d4af37]">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            {{ $instruction->submissions_count }} dikumpulkan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('instructor.instructions.show', $instruction) }}" 
                            class="inline-flex items-center px-4 py-2 rounded-xl border border-[#d4af37] text-[#d4af37] hover:bg-[#d4af37] hover:text-[#0b1221] transition-all text-sm font-bold">
                            Lihat
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-12 text-center">
                <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Belum Ada Instruksi</h3>
                <p class="text-gray-400">Buat instruksi atau tugas pertama untuk filter ini.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $instructions->links() }}
    </div>
</div>
@endsection
