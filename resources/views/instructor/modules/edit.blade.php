@extends('instructor.layouts.app')

@section('title', 'Edit Materi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

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
        <a href="{{ route('instructor.modules.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Gudang Modul</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">Edit</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
            <svg class="w-6 h-6 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-white tracking-wide">Edit Materi</h1>
            <p class="text-sm text-gray-400">{{ $module->title }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-900/30 border border-red-500/50 text-red-300 p-4 rounded-xl mb-6">
            <p class="font-semibold text-sm mb-1">Terjadi kesalahan:</p>
            <ul class="text-sm space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900/30 border border-red-500/50 text-red-300 p-4 rounded-xl mb-6">
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('instructor.modules.update', $module) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Target Selection --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-xl overflow-hidden mb-6">
            <div class="p-6 border-b border-[#1e293b]">
                <h2 class="text-lg font-bold text-white">Target Materi</h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Year --}}
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Tahun Periode <span class="text-red-400">*</span>
                    </label>
                    <select name="training_year_id" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37]">
                        @foreach($trainingYears as $year)
                            <option value="{{ $year->id }}" {{ old('training_year_id', $module->training_year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Class Type --}}
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Tipe Kelas <span class="text-red-400">*</span>
                    </label>
                    <select name="class_type" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37]">
                        @if($canTeachReguler && $canTeachKaryawan)
                            <option value="all" {{ $module->class_type == 'all' || $module->class_type == 'both' ? 'selected' : '' }}>Semua (Reguler & Karyawan)</option>
                            <option value="reguler" {{ $module->class_type == 'reguler' ? 'selected' : '' }}>Reguler</option>
                            <option value="karyawan" {{ $module->class_type == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                        @elseif($canTeachReguler)
                            <option value="reguler" {{ $module->class_type == 'reguler' ? 'selected' : '' }}>Reguler</option>
                        @elseif($canTeachKaryawan)
                            <option value="karyawan" {{ $module->class_type == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                        @endif
                    </select>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-xl overflow-hidden mb-6">
            <div class="p-6 border-b border-[#1e293b]">
                <h2 class="text-lg font-bold text-white">Konten Materi</h2>
            </div>
            <div class="p-6 space-y-6">
                {{-- Title --}}
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Judul Materi <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $module->title) }}" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Deskripsi
                    </label>
                    <textarea name="description" rows="4"
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all resize-none">{{ old('description', $module->description) }}</textarea>
                </div>

                {{-- Current File --}}
                @if($module->file_path)
                    <div class="flex items-center gap-3 p-4 bg-[#1e293b]/50 rounded-xl">
                        <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-white text-sm font-semibold">{{ basename($module->file_path) }}</p>
                            <p class="text-xs text-gray-400">File saat ini</p>
                        </div>
                        <a href="{{ asset('storage/' . $module->file_path) }}" target="_blank" class="text-[#d4af37] text-sm hover:underline">
                            Lihat
                        </a>
                    </div>
                @endif

                {{-- File Upload --}}
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        {{ $module->file_path ? 'Ganti File (Opsional)' : 'File Materi' }}
                    </label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.jpg,.jpeg,.png"
                        class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-[#1e293b] file:text-gray-300 hover:file:bg-[#334155]">
                    <p class="text-xs text-gray-500 mt-2">Format: PDF, DOC, PPT, XLS, ZIP, Gambar. Max 50MB.</p>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-4">
            <a href="{{ route('instructor.modules.index') }}" 
                class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                BATAL
            </a>
            <button type="submit" 
                class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                SIMPAN PERUBAHAN
            </button>
        </div>
    </form>
</div>
@endsection
