@extends('instructor.layouts.app')

@section('title', isset($quiz) ? 'Edit Quiz' : 'Buat Quiz')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" 
    x-data="quizBuilder(@js(isset($quiz) ? $quiz->questions->map(function($q) { 
        return [
            'question_text' => $q->question_text,
            'explanation' => $q->explanation,
            'image_url' => $q->image_url,
            'audio_url' => $q->audio_url,
            'options' => $q->options->map(fn($o) => ['text' => $o->option_text])->toArray(),
            'correct_option' => $q->options->search(fn($o) => $o->is_correct)
        ];
    })->toArray() : []))">

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
        <a href="{{ route('instructor.quizzes.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Kelola Quiz</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">{{ isset($quiz) ? 'Edit Quiz' : 'Buat Quiz' }}</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
            <svg class="w-6 h-6 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-white tracking-wide">{{ isset($quiz) ? 'Edit Quiz' : 'Buat Quiz Baru' }}</h1>
            <p class="text-sm text-gray-400">Buat soal pilihan ganda dengan dukungan audio untuk ujian JLPT</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ isset($quiz) ? route('instructor.quizzes.update', $quiz) : route('instructor.quizzes.store') }}" method="POST" enctype="multipart/form-data" @submit="validateForm">
        @csrf
        @if(isset($quiz))
            @method('PUT')
        @endif

        {{-- Error Display --}}
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

        {{-- Quiz Info Section --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden mb-6">
            <div class="p-6 border-b border-[#1e293b]">
                <h2 class="text-lg font-bold text-white">Informasi Quiz</h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Judul Quiz <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $quiz->title ?? '') }}" required
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all"
                            placeholder="Contoh: Ujian Tengah Semester">
                    </div>
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Gelombang Peserta
                        </label>
                        <select name="training_batch_id" 
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                            <option value="">Semua Gelombang</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ old('training_batch_id', $quiz->training_batch_id ?? '') == $batch->id ? 'selected' : '' }}>
                                    {{ $batch->trainingYear->name ?? '' }} - {{ $batch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all resize-none"
                        placeholder="Deskripsi singkat tentang quiz ini...">{{ old('description', $quiz->description ?? '') }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Durasi (Menit) <span class="text-red-400">*</span>
                        </label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $quiz->duration_minutes ?? 60) }}" required min="1" max="300"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                    </div>
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Nilai Minimum Lulus (KKM) <span class="text-red-400">*</span>
                        </label>
                        <input type="number" name="passing_score" value="{{ old('passing_score', $quiz->passing_score ?? 70) }}" required min="0" max="100"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                    </div>
                </div>

                {{-- Advanced Options --}}
                <div class="pt-6 border-t border-[#1e293b]">
                    <h3 class="text-sm font-bold text-white mb-4">Pengaturan Lanjutan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Access Code Toggle --}}
                        <div x-data="{ useCode: {{ isset($quiz) && $quiz->access_code ? 'true' : 'false' }} }">
                            <label class="flex items-center gap-3 cursor-pointer mb-2">
                                <input type="checkbox" name="use_access_code" value="1" x-model="useCode"
                                    {{ old('use_access_code', isset($quiz) && $quiz->access_code ? true : false) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-[#1e293b] bg-[#0b1221] text-[#d4af37] focus:ring-[#d4af37]/50">
                                <span class="text-white text-sm font-medium">Gunakan Kode Akses (Token)</span>
                            </label>
                            <p class="text-xs text-gray-500 ml-8">Siswa perlu memasukkan kode 6 digit sebelum mengerjakan</p>
                            @if(isset($quiz) && $quiz->access_code)
                                <div x-show="useCode" class="mt-3 ml-8 flex items-center gap-2">
                                    <span class="px-3 py-2 bg-[#0b1221] border border-[#1e293b] rounded-lg text-[#d4af37] font-mono font-bold tracking-widest">{{ $quiz->access_code }}</span>
                                    <form action="{{ route('instructor.quizzes.regenerate_code', $quiz) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-gray-400 hover:text-[#d4af37]">🔄 Generate Baru</button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        {{-- Show Answers Toggle --}}
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer mb-2">
                                <input type="checkbox" name="show_answers_after" value="1"
                                    {{ old('show_answers_after', $quiz->show_answers_after ?? false) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-[#1e293b] bg-[#0b1221] text-[#d4af37] focus:ring-[#d4af37]/50">
                                <span class="text-white text-sm font-medium">Tampilkan Pembahasan Setelah Ujian</span>
                            </label>
                            <p class="text-xs text-gray-500 ml-8">Siswa dapat melihat kunci jawaban setelah submit</p>
                        </div>

                        {{-- Shuffle Questions --}}
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer mb-2">
                                <input type="checkbox" name="shuffle_questions" value="1"
                                    {{ old('shuffle_questions', $quiz->shuffle_questions ?? false) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-[#1e293b] bg-[#0b1221] text-[#d4af37] focus:ring-[#d4af37]/50">
                                <span class="text-white text-sm font-medium">Acak Urutan Soal</span>
                            </label>
                            <p class="text-xs text-gray-500 ml-8">Setiap siswa mendapat urutan soal berbeda</p>
                        </div>

                        {{-- Shuffle Options --}}
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer mb-2">
                                <input type="checkbox" name="shuffle_options" value="1"
                                    {{ old('shuffle_options', $quiz->shuffle_options ?? false) ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-[#1e293b] bg-[#0b1221] text-[#d4af37] focus:ring-[#d4af37]/50">
                                <span class="text-white text-sm font-medium">Acak Pilihan Jawaban</span>
                            </label>
                            <p class="text-xs text-gray-500 ml-8">Urutan A/B/C/D diacak per siswa</p>
                        </div>
                    </div>
                </div>

                @if(isset($quiz))
                <div class="pt-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $quiz->is_active) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-[#1e293b] bg-[#0b1221] text-[#d4af37] focus:ring-[#d4af37]/50">
                        <span class="text-white text-sm font-medium">Quiz Aktif (dapat dikerjakan peserta)</span>
                    </label>
                </div>
                @endif
        </div>
        </div>

        {{-- Import Section (Inside main form - file will be processed when quiz is stored) --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-green-500/30 rounded-2xl shadow-2xl overflow-hidden mb-6">
            <div class="p-6 border-b border-[#1e293b]">
                <h2 class="text-lg font-bold text-white">📥 Import Soal dari Excel</h2>
                <p class="text-sm text-gray-400">Tambahkan banyak soal sekaligus menggunakan file Excel</p>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-4 items-center mb-4">
                    <a href="{{ route('instructor.quizzes.template') }}" 
                        class="inline-flex items-center px-4 py-2 rounded-xl bg-[#1e293b] text-gray-300 hover:text-[#d4af37] hover:bg-[#334155] transition-all text-sm font-bold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Template
                    </a>
                </div>
                <div x-data="{ fileName: '', hasFile: false }" class="flex flex-wrap items-center gap-4">
                    <label class="inline-flex items-center px-4 py-2 rounded-xl border border-[#1e293b] text-gray-300 hover:text-[#d4af37] hover:border-[#d4af37] transition-all text-sm font-bold cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Pilih File Excel
                        <input type="file" name="import_file" accept=".xlsx,.xls" class="hidden"
                            @change="fileName = $event.target.files[0]?.name || ''; hasFile = $event.target.files.length > 0">
                    </label>
                    <span x-show="fileName" class="text-sm text-[#d4af37] font-medium" x-text="fileName"></span>
                    <span x-show="hasFile" class="inline-flex items-center px-3 py-1 rounded-lg bg-green-500/10 text-green-400 text-xs font-bold">
                        ✓ File akan diimport saat menyimpan quiz
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-3">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Soal dari Excel akan ditambahkan ke daftar soal yang sudah dibuat di bawah
                </p>
            </div>
        </div>

        {{-- Questions Section --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden mb-6">
            <div class="p-6 border-b border-[#1e293b] flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-bold text-white">Daftar Soal</h2>
                    <p class="text-sm text-gray-400" x-text="'Total: ' + questions.length + ' soal'"></p>
                </div>
                <button type="button" @click="addQuestion()"
                    class="inline-flex items-center px-4 py-2 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] transition-all text-sm font-bold">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    TAMBAH SOAL
                </button>
            </div>
            
            <div class="p-6 space-y-6">
                {{-- Question Loop --}}
                <template x-for="(question, qIndex) in questions" :key="qIndex">
                    <div class="bg-[#0b1221] border border-[#1e293b] rounded-xl p-6 relative">
                        {{-- Question Header --}}
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-[#d4af37]/10 text-[#d4af37] text-sm font-bold">
                                Soal #<span x-text="qIndex + 1"></span>
                            </span>
                            <button type="button" @click="removeQuestion(qIndex)" x-show="questions.length > 1"
                                class="text-gray-400 hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Question Text --}}
                        <div class="mb-4">
                            <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Pertanyaan <span class="text-red-400">*</span></label>
                            <textarea x-model="question.question_text" :name="'questions[' + qIndex + '][question_text]'" rows="3" required
                                class="w-full bg-[#0f172a] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all resize-none"
                                placeholder="Tulis pertanyaan..."></textarea>
                        </div>

                        {{-- Media Upload Row --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            {{-- Image Upload --}}
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Gambar (Opsional)
                                </label>
                                <input type="file" :name="'questions[' + qIndex + '][image]'" accept="image/*"
                                    class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-[#1e293b] file:text-gray-300 hover:file:bg-[#334155]">
                                <template x-if="question.image_url">
                                    <div class="mt-2 flex items-center gap-2">
                                        <img :src="'/storage/' + question.image_url" class="h-16 rounded-lg border border-[#1e293b]">
                                        <input type="hidden" :name="'questions[' + qIndex + '][existing_image]'" :value="question.image_url">
                                        <button type="button" @click="question.image_url = ''" 
                                            class="p-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors"
                                            title="Hapus gambar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            {{-- Audio Upload --}}
                            <div>
                                <label class="block text-gray-400 text-xs font-bold uppercase mb-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                                    </svg>
                                    Audio / Listening (Opsional)
                                </label>
                                <input type="file" :name="'questions[' + qIndex + '][audio]'" accept="audio/*,.mp3,.wav,.ogg"
                                    class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-[#1e293b] file:text-gray-300 hover:file:bg-[#334155]">
                                <template x-if="question.audio_url">
                                    <div class="mt-2 flex items-center gap-2">
                                        <audio controls class="h-10 flex-1">
                                            <source :src="'/storage/' + question.audio_url">
                                        </audio>
                                        <input type="hidden" :name="'questions[' + qIndex + '][existing_audio]'" :value="question.audio_url">
                                        <button type="button" @click="question.audio_url = ''" 
                                            class="p-1.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition-colors"
                                            title="Hapus audio">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>


                        {{-- Explanation --}}
                        <div class="mb-4">
                            <label class="block text-gray-400 text-xs font-bold uppercase mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                Pembahasan (Opsional)
                            </label>
                            <textarea x-model="question.explanation" :name="'questions[' + qIndex + '][explanation]'" rows="2"
                                class="w-full bg-[#0f172a] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all resize-none text-sm"
                                placeholder="Penjelasan jawaban benar (ditampilkan setelah ujian jika diaktifkan)..."></textarea>
                        </div>

                        {{-- Options --}}
                        <div class="space-y-3">
                            <label class="block text-gray-400 text-xs font-bold uppercase">Pilihan Jawaban</label>
                            <template x-for="(option, oIndex) in question.options" :key="oIndex">
                                <div class="flex items-center gap-3">
                                    <input type="radio" :name="'questions[' + qIndex + '][correct_option]'" :value="oIndex" 
                                        x-model.number="question.correct_option"
                                        class="w-5 h-5 border-[#1e293b] bg-[#0f172a] text-[#d4af37] focus:ring-[#d4af37]/50">
                                    <input type="text" x-model="option.text" :name="'questions[' + qIndex + '][options][' + oIndex + '][text]'" required
                                        class="flex-1 bg-[#0f172a] border border-[#1e293b] text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all text-sm"
                                        :placeholder="'Pilihan ' + String.fromCharCode(65 + oIndex)">
                                    <button type="button" @click="removeOption(qIndex, oIndex)" x-show="question.options.length > 2"
                                        class="text-gray-500 hover:text-red-400 transition-colors p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="addOption(qIndex)" x-show="question.options.length < 6"
                                class="text-[#d4af37] text-xs font-bold hover:underline mt-2">
                                + Tambah Pilihan
                            </button>
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-4">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Pilih radio button untuk menandai jawaban yang benar
                        </p>
                    </div>
                </template>

                {{-- Empty State --}}
                <div x-show="questions.length === 0" class="text-center py-12">
                    <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-400 mb-4">Belum ada soal. Klik tombol "Tambah Soal" untuk mulai.</p>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-4">
            <a href="{{ route('instructor.quizzes.index') }}" 
                class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                BATAL
            </a>
            <button type="submit" 
                class="inline-flex justify-center items-center px-6 py-3 rounded-xl bg-[#d4af37] text-[#0b1221] hover:bg-[#b8962e] transition-all duration-200 text-sm font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ isset($quiz) ? 'SIMPAN PERUBAHAN' : 'SIMPAN QUIZ' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function quizBuilder(existingQuestions = []) {
    return {
        questions: existingQuestions.length > 0 ? existingQuestions : [
            {
                question_text: '',
                explanation: '',
                image_url: '',
                audio_url: '',
                options: [
                    { text: '' },
                    { text: '' },
                    { text: '' },
                    { text: '' }
                ],
                correct_option: 0
            }
        ],
        
        addQuestion() {
            this.questions.push({
                question_text: '',
                explanation: '',
                image_url: '',
                audio_url: '',
                options: [
                    { text: '' },
                    { text: '' },
                    { text: '' },
                    { text: '' }
                ],
                correct_option: 0
            });
        },
        
        removeQuestion(index) {
            if (this.questions.length > 1) {
                this.questions.splice(index, 1);
            }
        },
        
        addOption(qIndex) {
            if (this.questions[qIndex].options.length < 6) {
                this.questions[qIndex].options.push({ text: '' });
            }
        },
        
        removeOption(qIndex, oIndex) {
            if (this.questions[qIndex].options.length > 2) {
                this.questions[qIndex].options.splice(oIndex, 1);
                // Reset correct_option if removed
                if (this.questions[qIndex].correct_option >= this.questions[qIndex].options.length) {
                    this.questions[qIndex].correct_option = 0;
                }
            }
        },
        
        validateForm(event) {
            if (this.questions.length === 0) {
                alert('Tambahkan minimal 1 soal!');
                event.preventDefault();
                return false;
            }
            
            for (let i = 0; i < this.questions.length; i++) {
                const q = this.questions[i];
                if (!q.question_text.trim()) {
                    alert(`Soal #${i + 1}: Pertanyaan tidak boleh kosong!`);
                    event.preventDefault();
                    return false;
                }
                for (let j = 0; j < q.options.length; j++) {
                    if (!q.options[j].text.trim()) {
                        alert(`Soal #${i + 1}: Pilihan ${String.fromCharCode(65 + j)} tidak boleh kosong!`);
                        event.preventDefault();
                        return false;
                    }
                }
            }
            return true;
        }
    }
}
</script>
@endpush
@endsection
