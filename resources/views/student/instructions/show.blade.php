@extends('student.layouts.app')

@section('title', $instruction->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-gray-400 hover:text-[#d4af37] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('student.instructions.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Papan Instruksi</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">{{ Str::limit($instruction->title, 30) }}</span>
    </nav>

    @if(session('success'))
        <div class="bg-green-900/30 border border-green-500/50 text-green-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-900/30 border border-red-500/50 text-red-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border {{ $instruction->is_task ? 'border-orange-500/30' : 'border-[#1e293b]' }} rounded-2xl shadow-xl overflow-hidden">
        <div class="p-6">
            {{-- Header --}}
            <div class="flex items-start gap-4 mb-6">
                <div class="h-12 w-12 rounded-xl {{ $instruction->is_task ? 'bg-orange-500/10' : 'bg-[#1e293b]' }} flex items-center justify-center flex-shrink-0">
                    @if($instruction->is_task)
                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    @else
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        @if($instruction->is_task)
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-orange-500/10 text-orange-400">TUGAS</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#1e293b] text-gray-400">MATERI</span>
                        @endif
                        <span class="text-xs text-gray-500">{{ $instruction->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <h1 class="text-xl font-bold text-white">{{ $instruction->title }}</h1>
                    <p class="text-sm text-gray-400 mt-1">Oleh: {{ $instruction->instructor->name ?? 'Pengajar' }}</p>
                </div>
            </div>

            {{-- Task Meta --}}
            @if($instruction->is_task)
                <div class="flex flex-wrap items-center gap-4 p-4 bg-[#1e293b]/50 rounded-xl mb-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 {{ $instruction->isDeadlinePassed() ? 'text-red-400' : 'text-orange-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm {{ $instruction->isDeadlinePassed() ? 'text-red-400' : 'text-white' }}">
                            Deadline: {{ $instruction->deadline?->format('d M Y, H:i') ?? '-' }}
                            @if($instruction->isDeadlinePassed())
                                <span class="text-red-400">(Berakhir)</span>
                            @endif
                        </span>
                    </div>
                </div>
            @endif

            {{-- Description --}}
            @if($instruction->description)
                <div class="prose prose-invert max-w-none mb-6">
                    {!! nl2br(e($instruction->description)) !!}
                </div>
            @endif

            {{-- Attachment --}}
            @if($instruction->file_path)
                <div class="flex items-center gap-3 p-4 bg-[#1e293b]/50 rounded-xl mb-6">
                    <svg class="w-8 h-8 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-white font-semibold">{{ basename($instruction->file_path) }}</p>
                        <p class="text-xs text-gray-400">Lampiran</p>
                    </div>
                    <a href="{{ asset('storage/' . $instruction->file_path) }}" target="_blank" download
                        class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </a>
                </div>
            @endif

            {{-- Submission Section --}}
            @if($instruction->is_task)
                <div class="pt-6 border-t border-[#1e293b]">
                    @if($submission)
                        {{-- Already submitted --}}
                        <div class="p-4 bg-green-900/20 border border-green-500/30 rounded-xl">
                            <div class="flex items-center gap-3 mb-3">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-green-400 font-semibold">Tugas Sudah Dikumpulkan</p>
                                    <p class="text-xs text-gray-400">{{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                                </div>
                                @if($submission->grade !== null)
                                    <span class="ml-auto px-4 py-2 rounded-xl {{ $submission->grade >= 70 ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }} font-bold">
                                        Nilai: {{ $submission->grade }}
                                    </span>
                                @endif
                            </div>
                            @if($submission->file_path)
                                <p class="text-sm text-gray-400 mb-2">File: <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-[#d4af37] hover:underline">Lihat</a></p>
                            @endif
                            @if($submission->text_response)
                                <p class="text-sm text-gray-400">Jawaban Teks:</p>
                                <div class="p-3 bg-[#0b1221] rounded-lg mt-1 text-sm text-white">{{ $submission->text_response }}</div>
                            @endif
                            @if($submission->feedback)
                                <p class="text-sm text-gray-400 mt-3">Feedback:</p>
                                <div class="p-3 bg-[#0b1221] rounded-lg mt-1 text-sm text-[#d4af37]">{{ $submission->feedback }}</div>
                            @endif
                        </div>
                    @elseif($instruction->isDeadlinePassed())
                        {{-- Deadline passed --}}
                        <div class="p-4 bg-red-900/20 border border-red-500/30 rounded-xl">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-red-400 font-semibold">Batas Waktu Terlewat</p>
                                    <p class="text-xs text-gray-400">Anda tidak mengumpulkan tugas ini tepat waktu.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Submission form --}}
                        <form action="{{ route('student.instructions.submit', $instruction) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <h4 class="text-sm font-bold text-orange-400 uppercase tracking-widest">Kumpulkan Jawaban</h4>
                            
                            @if(in_array($instruction->allowed_response_type, ['file', 'both']))
                                <div>
                                    <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Upload File</label>
                                    <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip"
                                        class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-[#1e293b] file:text-gray-300 hover:file:bg-[#334155]">
                                    <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, JPG, PNG, ZIP. Max 50MB.</p>
                                </div>
                            @endif

                            @if(in_array($instruction->allowed_response_type, ['text', 'both']))
                                <div>
                                    <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Jawaban Teks</label>
                                    <textarea name="text_response" rows="5"
                                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all resize-none"
                                        placeholder="Tulis jawaban Anda di sini..."></textarea>
                                </div>
                            @endif

                            <button type="submit" 
                                class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                KUMPULKAN TUGAS
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Back button --}}
    <div class="mt-6">
        <a href="{{ route('student.instructions.index') }}" 
            class="inline-flex items-center text-gray-400 hover:text-[#d4af37] transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Papan Instruksi
        </a>
    </div>
</div>
@endsection
