@extends('instructor.layouts.app')

@section('title', 'Kelas Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ classTab: '{{ $selectedClassType }}' }">

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
        <span class="text-[#d4af37] font-medium">Kelas Saya</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Kelas Saya</h1>
                <p class="text-sm text-gray-400">Daftar peserta yang Anda ampu</p>
            </div>
        </div>
    </div>

    {{-- Period Selection --}}
    @if($trainingYears->count() > 0)
    <div class="mb-6">
        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold mb-3">Pilih Tahun Pelatihan</p>
        <div class="flex flex-wrap gap-2">
            @foreach($trainingYears as $year)
                <a href="{{ route('instructor.students.index', ['year' => $year->id]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-bold transition-all {{ $selectedYearId == $year->id ? 'bg-[#d4af37] text-[#0b1221]' : 'bg-[#0f172a] border border-[#1e293b] text-gray-300 hover:border-[#d4af37]/50' }}">
                    {{ $year->name }}
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Class Type Tabs & Student Table --}}
    @if($selectedYear)
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        {{-- Class Type Tabs --}}
        <div class="p-6 border-b border-[#1e293b]">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-white">Daftar Peserta</h2>
                    <p class="text-sm text-gray-400">Periode {{ $selectedYear->name ?? '' }}</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" @click="classTab = 'reguler'"
                            :class="classTab === 'reguler' ? 'bg-blue-500 text-white border-blue-500' : 'bg-[#0b1221] text-gray-300 border-[#1e293b] hover:border-blue-500/50'"
                            class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider border transition-all">
                        Reguler ({{ $regulerStudents->count() }})
                    </button>
                    <button type="button" @click="classTab = 'karyawan'"
                            :class="classTab === 'karyawan' ? 'bg-purple-500 text-white border-purple-500' : 'bg-[#0b1221] text-gray-300 border-[#1e293b] hover:border-purple-500/50'"
                            class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider border transition-all">
                        Karyawan ({{ $karyawanStudents->count() }})
                    </button>
                </div>
            </div>
        </div>

        {{-- Reguler Table --}}
        <div x-show="classTab === 'reguler'">
            @if($regulerStudents->count() === 0)
                <div class="p-12">
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="h-16 w-16 rounded-full bg-blue-500/10 border border-blue-500/30 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        @if(!$canTeachReguler)
                            <p class="text-gray-300 font-bold">Anda belum ditugaskan mengajar kelas Reguler</p>
                            <p class="text-gray-500 text-sm mt-2">Hubungi admin jika ada perubahan penugasan.</p>
                        @else
                            <p class="text-gray-300 font-bold">Belum ada peserta Reguler di tahun ini</p>
                            <p class="text-gray-500 text-sm mt-2">Peserta akan muncul setelah didaftarkan oleh admin.</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#0b1221] text-blue-400 border-b border-[#1e293b] text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">No</th>
                                <th class="px-6 py-4 font-bold">Nama Lengkap</th>
                                <th class="px-6 py-4 font-bold">Email</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e293b] text-gray-300 text-sm">
                            @foreach($regulerStudents as $index => $student)
                            <tr class="hover:bg-[#1e293b]/50 transition duration-150">
                                <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-white">
                                    {{ $student->user->name ?? $student->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-400">{{ $student->user->email ?? $student->email ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Karyawan Table --}}
        <div x-show="classTab === 'karyawan'" x-cloak>
            @if($karyawanStudents->count() === 0)
                <div class="p-12">
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="h-16 w-16 rounded-full bg-purple-500/10 border border-purple-500/30 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        @if(!$canTeachKaryawan)
                            <p class="text-gray-300 font-bold">Anda belum ditugaskan mengajar kelas Karyawan</p>
                            <p class="text-gray-500 text-sm mt-2">Hubungi admin jika ada perubahan penugasan.</p>
                        @else
                            <p class="text-gray-300 font-bold">Belum ada peserta Karyawan di tahun ini</p>
                            <p class="text-gray-500 text-sm mt-2">Peserta akan muncul setelah didaftarkan oleh admin.</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#0b1221] text-purple-400 border-b border-[#1e293b] text-xs uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">No</th>
                                <th class="px-6 py-4 font-bold">Nama Lengkap</th>
                                <th class="px-6 py-4 font-bold">Email</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e293b] text-gray-300 text-sm">
                            @foreach($karyawanStudents as $index => $student)
                            <tr class="hover:bg-[#1e293b]/50 transition duration-150">
                                <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-white">
                                    {{ $student->user->name ?? $student->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-gray-400">{{ $student->user->email ?? $student->email ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    @else
    <div class="bg-[#0f172a] border border-[#1e293b] rounded-xl p-8 mb-6 text-center">
        <p class="text-gray-400">Belum ada tahun pelatihan yang tersedia</p>
    </div>
    @endif

    {{-- No Teacher Warning --}}
    @if(!$teacher)
    <div class="bg-red-900/20 border border-red-500/30 rounded-xl p-6 text-center mt-6">
        <p class="text-red-300 font-bold">Data instruktur belum terhubung</p>
        <p class="text-red-400/70 text-sm mt-2">Hubungi admin untuk mengatur data Teacher Anda.</p>
    </div>
    @endif

</div>
@endsection
