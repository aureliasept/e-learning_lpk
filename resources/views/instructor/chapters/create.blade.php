@extends('instructor.layouts.app')

@section('title', isset($chapter) ? 'Edit Pertemuan' : 'Tambah Pertemuan')

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
        <a href="{{ route('instructor.courses.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Kelas Saya</a>
        @if(isset($course) && $course)
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('instructor.courses.show', $course->id) }}" class="text-gray-400 hover:text-[#d4af37] transition">{{ Str::limit($course->title ?? $course->name, 20) }}</a>
        @endif
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">{{ isset($chapter) ? 'Edit Pertemuan' : 'Tambah Pertemuan' }}</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-6 h-6 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">{{ isset($chapter) ? 'Edit Pertemuan' : 'Tambah Pertemuan' }}</h1>
                <p class="text-sm text-gray-400">Tulis instruksi teks untuk papan pengumuman</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        <form action="{{ isset($chapter) ? route('instructor.chapters.update', $chapter->id) : route('instructor.chapters.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($chapter))
                @method('PUT')
            @endif

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

            <input type="hidden" name="course_id" value="{{ old('course_id', $course->id ?? null) }}">

            <div class="p-8 space-y-6">
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Pilih Modul (Referensi) <span class="text-red-400">*</span>
                    </label>
                    <select name="module_id" required 
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all appearance-none">
                        <option value="">Pilih Modul</option>
                        @foreach(($modules ?? collect()) as $m)
                            <option value="{{ $m->id }}" {{ (string) old('module_id', $chapter->module_id ?? request('module_id')) === (string) $m->id ? 'selected' : '' }}>
                                {{ $m->title ?? 'Modul #' . $m->id }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-gray-500">Pertemuan ini akan terkait ke modul di atas (untuk grouping).</p>
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Judul Pertemuan <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $chapter->title ?? '') }}" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all"
                        placeholder="Contoh: Pertemuan 1 - Pengenalan">
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                        Instruksi <span class="text-red-400">*</span>
                    </label>
                    <textarea name="instruction" rows="7" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all resize-none"
                        placeholder="Tulis instruksi untuk siswa, misalnya: tugas minggu ini, materi yang harus dipelajari, deadline, link tambahan, dll.">{{ old('instruction', $chapter->instruction ?? '') }}</textarea>
                    <p class="mt-2 text-xs text-gray-500">Contoh: tugas minggu ini, materi yang harus dipelajari, deadline, link tambahan.</p>
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">File Lampiran (PDF/DOC/DOCX)</label>
                    @if(isset($chapter) && !empty($chapter->file_path))
                        <div class="bg-[#0b1221] rounded-xl p-4 border border-[#1e293b] mb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-[#1e293b] flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-300">File saat ini:</p>
                                    <a href="{{ asset('storage/' . $chapter->file_path) }}" class="text-[#d4af37] text-sm hover:underline" target="_blank">Download File</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="file" accept=".pdf,.doc,.docx"
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#d4af37] file:text-[#0b1221] file:font-bold hover:file:bg-[#b8962e] transition-all">
                    <p class="mt-2 text-xs text-gray-500">Maks 10MB. Format: PDF, DOC, DOCX.@if(isset($chapter)) Jika kosong, file tidak berubah.@endif</p>
                </div>

                {{-- Footer Actions --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 border-t border-[#1e293b] pt-6">
                    @php
                        $backCourseId = old('course_id', $course->id ?? null);
                    @endphp
                    <a href="{{ $backCourseId ? route('instructor.courses.show', $backCourseId) : route('instructor.dashboard') }}" 
                        class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                        BATAL
                    </a>
                    <button type="submit" 
                        class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                        {{ isset($chapter) ? 'SIMPAN PERUBAHAN' : 'SIMPAN PERTEMUAN' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
