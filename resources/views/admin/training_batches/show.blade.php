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
            <a href="{{ route('admin.training_years.show', $year->id) }}" class="text-gray-400 hover:text-[#d4af37] transition">Tahun {{ $year->name }}</a>
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-[#d4af37] font-medium">{{ $batch->name }}</span>
        </nav>

        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                    <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">{{ $batch->name }}</h1>
                    <p class="text-sm text-gray-400">Tahun {{ $year->name }} • {{ $batch->date_range }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.training_years.show', $year->id) }}" 
                    class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl bg-[#1e293b] text-gray-400 hover:text-white border border-[#334155] hover:bg-[#334155] transition-all duration-200 text-sm font-semibold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    KEMBALI
                </a>
                <a href="{{ route('admin.students.create', ['batch' => $batch->id]) }}" 
                    class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                    TAMBAH SISWA
                </a>
            </div>
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

        {{-- Batch Status & Info --}}
        @php
            $statusColor = $batch->status_color;
        @endphp
        <div class="bg-gradient-to-r from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-xl p-4 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-4 py-1.5 rounded-lg {{ $statusColor['bg'] }} {{ $statusColor['text'] }} border {{ $statusColor['border'] }} text-xs font-bold uppercase">
                            {{ $batch->status_label }}
                        </span>
                    </div>
                    <div class="h-8 w-px bg-[#1e293b]"></div>
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-[#d4af37]/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-white leading-none">{{ $students->total() }}</p>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Total Siswa</p>
                        </div>
                    </div>
                    <div class="h-8 w-px bg-[#1e293b]"></div>
                    {{-- Type Filter Tabs --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.training_batches.show', $batch->id) }}" 
                            class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase transition-all duration-200 {{ !$selectedType ? 'bg-[#d4af37] text-[#0b1221]' : 'bg-[#1e293b] text-gray-400 hover:text-white border border-[#334155]' }}">
                            Semua
                        </a>
                        <a href="{{ route('admin.training_batches.show', ['training_batch' => $batch->id, 'type' => 'reguler']) }}" 
                            class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase transition-all duration-200 {{ $selectedType === 'reguler' ? 'bg-[#d4af37] text-[#0b1221]' : 'bg-[#1e293b] text-gray-400 hover:text-white border border-[#334155]' }}">
                            Reguler
                        </a>
                        <a href="{{ route('admin.training_batches.show', ['training_batch' => $batch->id, 'type' => 'karyawan']) }}" 
                            class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase transition-all duration-200 {{ $selectedType === 'karyawan' ? 'bg-[#d4af37] text-[#0b1221]' : 'bg-[#1e293b] text-gray-400 hover:text-white border border-[#334155]' }}">
                            Karyawan
                        </a>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} siswa
                </div>
            </div>
        </div>


        {{-- Table Card --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[#0b1221]/80 border-b border-[#1e293b]">
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">Nama</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">Email</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider text-center">JK</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">No Telepon</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider text-center">Kelas</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">Tgl Masuk</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1e293b] text-gray-300 text-sm">
                        @forelse($students as $student)
                        <tr class="hover:bg-[#1e293b]/30 transition duration-150">
                            <td class="px-5 py-4">
                                <span class="font-medium text-white text-sm">{{ $student->user->name ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-4 text-gray-400 text-sm">{{ $student->user->email ?? '-' }}</td>
                            <td class="px-5 py-4 text-center">
                                @if($student->gender === 'L' || $student->gender === 'Laki-laki')
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-500/10 text-blue-400 text-xs font-bold">L</span>
                                @elseif($student->gender === 'P' || $student->gender === 'Perempuan')
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-pink-500/10 text-pink-400 text-xs font-bold">P</span>
                                @else
                                    <span class="text-gray-500 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-400 text-sm">{{ $student->phone ?? '-' }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center px-4 py-1.5 rounded-lg bg-[#1e293b] text-[#d4af37] border border-[#d4af37]/30 text-xs font-bold">
                                    {{ $student->classroom ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-400 text-sm">{{ $student->entry_date ? $student->entry_date->format('d/m/Y') : '-' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.students.edit', $student->id) }}" 
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#1e293b] text-gray-400 hover:text-[#d4af37] hover:bg-[#334155] transition-all duration-200" 
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    
                                    <button type="button" 
                                        x-data
                                        @click="$dispatch('confirm-delete', { 
                                            url: '{{ route('admin.students.destroy', $student->id) }}',
                                            title: 'Hapus Siswa',
                                            message: 'Apakah Anda yakin ingin menghapus data siswa {{ $student->user->name ?? 'ini' }}? Tindakan ini tidak dapat dibatalkan.'
                                        })"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#1e293b] text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all duration-200"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-400 font-medium">Belum ada siswa di gelombang ini</p>
                                        <p class="text-gray-500 text-sm mt-1">Klik tombol "Tambah Siswa" untuk menambahkan data baru</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if($students->hasPages())
            <div class="bg-[#0b1221]/50 px-6 py-4 border-t border-[#1e293b]">
                {{ $students->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
