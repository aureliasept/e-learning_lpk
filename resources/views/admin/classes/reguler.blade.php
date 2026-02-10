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
            <span class="text-[#d4af37] font-medium">Kelas Reguler</span>
        </nav>

        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                    <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">Kelas Reguler</h1>
                    <p class="text-sm text-gray-400">Kelola data siswa program reguler</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                {{-- Filter Tahun Pelatihan --}}
                <form method="GET" action="{{ route('admin.classes.reguler') }}" class="flex flex-wrap items-center gap-3">
                    <label class="text-xs font-bold uppercase text-gray-400 whitespace-nowrap">Tahun</label>
                    <select name="year" onchange="this.form.submit()" 
                        class="bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all min-w-[160px]">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ (string) $selectedYearId === (string) $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </form>

                {{-- Tombol Tambah --}}
                <a href="{{ route('admin.students.create', ['type' => 'reguler', 'year' => $selectedYearId]) }}" 
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

        {{-- Compact Stats Row --}}
        <div class="bg-gradient-to-r from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-xl p-4 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-[#d4af37]/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-white leading-none">{{ $students->total() }}</p>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Total</p>
                        </div>
                    </div>
                    <div class="h-8 w-px bg-[#1e293b]"></div>
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white leading-none">{{ $selectedYear->name ?? 'Semua' }}</p>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Filter</p>
                        </div>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    Menampilkan {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} siswa
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden shadow-2xl" x-data>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[#0b1221]/80 border-b border-[#1e293b]">
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">Nama</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">NIK</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider text-center">JK</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">No Telepon</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider text-center">Kelas</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider text-center">Tahun</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">Tgl Masuk</th>
                            <th class="px-5 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1e293b]/70">
                        @forelse($students as $student)
                        <tr class="hover:bg-[#1e293b]/40 transition-all duration-200 group">
                            <td class="px-5 py-4">
                                <span class="font-medium text-white text-sm">{{ $student->user->name ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-4 text-gray-400 text-sm font-mono">{{ $student->user->nik ?? '-' }}</td>
                            <td class="px-5 py-4 text-center">
                                @if($student->gender === 'L')
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-500/10 text-blue-400 text-xs font-bold">L</span>
                                @elseif($student->gender === 'P')
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-pink-500/10 text-pink-400 text-xs font-bold">P</span>
                                @else
                                    <span class="text-gray-500 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-gray-400 text-sm">{{ $student->phone ?? '-' }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-[#1e293b] text-[#d4af37] border border-[#d4af37]/30 text-xs font-bold">
                                    {{ $student->classroom ?? '-' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($student->trainingYear)
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-purple-500/10 text-purple-400 border border-purple-500/30 text-xs font-semibold">
                                        {{ $student->trainingYear->name }}
                                    </span>
                                @else
                                    <span class="text-gray-500 text-sm">-</span>
                                @endif
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
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-400 font-medium">Belum ada data siswa</p>
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
                {{ $students->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection