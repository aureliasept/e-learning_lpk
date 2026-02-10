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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">Tahun {{ $year->name }}</h1>
                    <p class="text-sm text-gray-400">Daftar peserta di tahun ini</p>
                </div>
            </div>
            <a href="{{ route('admin.training_years.index') }}" 
                class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl bg-[#1e293b] text-gray-400 hover:text-white border border-[#334155] hover:bg-[#334155] transition-all duration-200 text-sm font-semibold">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
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

        {{-- Stats Cards --}}
        <div class="flex flex-wrap gap-4 mb-6">
            <div class="bg-[#0f172a] border border-[#1e293b] rounded-xl px-5 py-4 flex items-center gap-4">
                <div class="h-10 w-10 rounded-lg bg-green-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $regulerCount }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Kelas Reguler</p>
                </div>
            </div>
            <div class="bg-[#0f172a] border border-[#1e293b] rounded-xl px-5 py-4 flex items-center gap-4">
                <div class="h-10 w-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-white">{{ $karyawanCount }}</p>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Kelas Karyawan</p>
                </div>
            </div>
        </div>

        {{-- Students Table --}}
        @if($students->count() > 0)
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden shadow-2xl">
                <div class="px-6 py-4 border-b border-[#1e293b]">
                    <h3 class="text-white font-semibold">Daftar Peserta</h3>
                    <p class="text-xs text-gray-500">{{ $students->count() }} peserta ditemukan</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#0b1221]/80 border-b border-[#1e293b]">
                                <th class="px-6 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">NIK</th>
                                <th class="px-6 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">Tipe</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e293b] text-gray-300 text-sm">
                            @foreach($students as $student)
                                <tr class="hover:bg-[#1e293b]/30 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-white">{{ $student->user->name ?? '-' }}</td>
                                    <td class="px-6 py-4 font-mono text-gray-400">{{ $student->user->nik ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $student->classroom ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if($student->type === 'karyawan')
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-purple-500/10 text-purple-400 border border-purple-500/30 text-xs font-semibold">
                                                Karyawan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-green-500/10 text-green-400 border border-green-500/30 text-xs font-semibold">
                                                Reguler
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-lg">
                <div class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center gap-4">
                        <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-400 font-medium">Belum ada peserta di tahun {{ $year->name }}</p>
                            <p class="text-gray-500 text-sm mt-1">Tambahkan peserta melalui menu Kelas Reguler atau Karyawan</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
