@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
        <a href="{{ route('admin.academic_years.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Periode Pelatihan</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">Tambah Periode</span>
    </nav>

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-6 h-6 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Tambah Periode Baru</h1>
                <p class="text-sm text-gray-400">Buat periode pelatihan / tahun ajaran baru</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        <form action="{{ route('admin.academic_years.store') }}" method="POST">
            @csrf

            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Nama Periode <span class="text-red-400">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: 2025/2026"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Tanggal Mulai <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required
                                class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Tanggal Selesai <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" required
                                class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl pl-12 pr-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                        </div>
                    </div>
                </div>

                <div class="bg-[#0b1221]/50 rounded-xl p-4 border border-[#1e293b]">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} 
                                class="peer sr-only">
                            <div class="w-11 h-6 bg-[#1e293b] rounded-full peer-checked:bg-[#d4af37] transition-colors"></div>
                            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                        </div>
                        <div>
                            <span class="text-white font-medium group-hover:text-[#d4af37] transition">Jadikan periode ini aktif</span>
                            <p class="text-xs text-gray-500">Periode aktif akan ditampilkan sebagai default di filter siswa</p>
                        </div>
                    </label>
                </div>

                {{-- Footer Actions --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 border-t border-[#1e293b] pt-6">
                    <a href="{{ route('admin.academic_years.index') }}" 
                        class="inline-flex justify-center items-center gap-2 px-6 py-3 rounded-xl border border-[#334155] text-gray-400 hover:text-white hover:bg-[#1e293b] hover:border-[#475569] transition-all duration-200 text-sm font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        BATAL
                    </a>
                    <button type="submit" 
                        class="inline-flex justify-center items-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-[#d4af37] to-[#b8962e] hover:from-[#e5c349] hover:to-[#d4af37] text-[#0b1221] font-bold shadow-lg shadow-[#d4af37]/25 transition-all duration-200 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        SIMPAN PERIODE
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
