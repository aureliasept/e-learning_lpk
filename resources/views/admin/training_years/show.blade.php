@extends('admin.layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-sm mb-6">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-[#d4af37] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </a>
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.training_years.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Periode Pelatihan</a>
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-[#d4af37] font-medium">Tahun {{ $year->name }}</span>
        </nav>

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                    <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">Tahun {{ $year->name }}</h1>
                    <p class="text-sm text-gray-400">Daftar gelombang pelatihan</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.training_years.index') }}" 
                    class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl bg-[#1e293b] text-gray-400 hover:text-white border border-[#334155] hover:bg-[#334155] transition-all duration-200 text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    KEMBALI
                </a>
                <a href="{{ route('admin.training_batches.create', ['year' => $year->id]) }}" 
                    class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                    TAMBAH GELOMBANG
                </a>
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

        {{-- Year Status Badge --}}
        <div class="mb-6">
            @if($year->is_active)
                <span class="inline-flex items-center px-4 py-2 rounded-xl bg-green-500/10 text-green-400 border border-green-500/30 text-sm font-semibold">
                    <span class="w-2 h-2 rounded-full bg-green-400 mr-2 animate-pulse"></span>
                    Tahun Aktif
                </span>
            @else
                <span class="inline-flex items-center px-4 py-2 rounded-xl bg-gray-500/10 text-gray-400 border border-gray-500/30 text-sm font-semibold">
                    Tahun Nonaktif
                </span>
            @endif
        </div>

        {{-- Batch Cards Grid --}}
        @if($batches->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($batches as $batch)
                    @php
                        $statusColor = $batch->status_color;
                    @endphp
                    <div class="group bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:border-[#d4af37]/50 transition-all duration-300">
                        
                        {{-- Card Header (clickable area) --}}
                        <a href="{{ route('admin.training_batches.show', $batch->id) }}" class="block px-6 pt-6 pb-4 hover:bg-[#1e293b]/30 transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-white group-hover:text-[#d4af37] transition-colors">
                                        {{ $batch->name }}
                                    </h3>
                                    <p class="text-sm text-gray-400 mt-1">
                                        {{ $batch->date_range }}
                                    </p>
                                </div>
                                {{-- Status Badge --}}
                                <span class="inline-flex items-center px-3 py-1 rounded-lg {{ $statusColor['bg'] }} {{ $statusColor['text'] }} border {{ $statusColor['border'] }} text-xs font-bold uppercase">
                                    {{ $batch->status_label }}
                                </span>
                            </div>
                        </a>

                        {{-- Card Footer with Actions --}}
                        <div class="px-6 py-4 bg-[#0b1221]/50 border-t border-[#1e293b] flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-lg font-bold text-white leading-none">{{ $batch->students_count }}</p>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Siswa</p>
                                </div>
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2">
                                {{-- Edit Button --}}
                                <a href="{{ route('admin.training_batches.edit', $batch->id) }}" 
                                   class="h-8 w-8 rounded-lg bg-blue-500/10 flex items-center justify-center hover:bg-blue-500/20 transition-colors" title="Edit Gelombang">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                {{-- Delete Button --}}
                                <button type="button" x-data
                                    @click="$dispatch('confirm-delete', { 
                                        url: '{{ route('admin.training_batches.destroy', $batch->id) }}',
                                        title: 'Hapus Gelombang',
                                        message: 'Apakah Anda yakin ingin menghapus gelombang {{ $batch->name }}? Semua data siswa yang terkait akan terpengaruh.'
                                    })"
                                    class="h-8 w-8 rounded-lg bg-red-500/10 flex items-center justify-center hover:bg-red-500/20 transition-colors" title="Hapus Gelombang">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                                {{-- View Arrow --}}
                                <a href="{{ route('admin.training_batches.show', $batch->id) }}" 
                                   class="h-8 w-8 rounded-lg bg-[#d4af37]/10 flex items-center justify-center hover:bg-[#d4af37]/20 transition-colors" title="Lihat Detail">
                                    <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-lg">
                <div class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center gap-4">
                        <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-400 font-medium">Belum ada gelombang di tahun {{ $year->name }}</p>
                            <p class="text-gray-500 text-sm mt-1">Klik tombol "Tambah Gelombang" untuk menambah gelombang baru</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Legend --}}
        <div class="mt-8 flex flex-wrap items-center gap-6 text-xs text-gray-400">
            <span class="font-medium text-gray-300">Keterangan Status:</span>
            <span class="inline-flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                Aktif (Sedang Berjalan)
            </span>
            <span class="inline-flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                Segera Datang
            </span>
            <span class="inline-flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                Selesai
            </span>
        </div>
    </div>
@endsection
