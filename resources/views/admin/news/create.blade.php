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
        <a href="{{ route('admin.news.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Berita & Info</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">Tulis Berita</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-6 h-6 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Tulis Berita / Info</h1>
                <p class="text-sm text-gray-400">Buat pengumuman baru untuk dashboard siswa</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
                <div class="mx-8 mt-8 bg-red-900/30 border border-red-500/50 text-red-300 p-4 rounded-xl">
                    <p class="font-semibold text-sm mb-1">Terjadi kesalahan:</p>
                    <ul class="text-sm space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Judul Berita <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Jadwal Libur Idul Fitri"
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all">
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Gambar Cover
                    </label>
                    <input type="file" name="image" accept="image/png,image/jpeg"
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#d4af37] file:text-[#0b1221] file:font-bold hover:file:bg-[#b8962e] transition-all">
                    <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG. Maks: 2MB</p>
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Isi Berita <span class="text-red-400">*</span>
                    </label>
                    <textarea name="content" rows="10" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all resize-none"
                        placeholder="Tulis informasi lengkap di sini...">{{ old('content') }}</textarea>
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">Status</label>
                    <select name="status" class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all appearance-none">
                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="publish" {{ old('status') === 'publish' ? 'selected' : '' }}>Publish</option>
                    </select>
                </div>

                {{-- Footer Actions --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 border-t border-[#1e293b] pt-6">
                    <a href="{{ route('admin.news.index') }}" 
                        class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                        BATAL
                    </a>
                    <button type="submit" 
                        class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                        KIRIM BERITA
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection