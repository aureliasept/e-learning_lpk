@extends('student.layouts.app')

@section('title', 'Papan Instruksi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
            <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-white tracking-wide">Papan Instruksi</h1>
            <p class="text-sm text-gray-400">{{ $student->trainingBatch->name ?? 'Belum ada gelombang' }}</p>
        </div>
    </div>

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

    {{-- Timeline --}}
    <div class="space-y-4">
        @forelse($instructions as $instruction)
            @php
                $submission = $instruction->submissions->first();
                $hasSubmitted = $submission !== null;
            @endphp
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border {{ $instruction->is_task ? 'border-orange-500/30' : 'border-[#1e293b]' }} rounded-2xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        {{-- Icon --}}
                        <div class="h-10 w-10 rounded-xl {{ $instruction->is_task ? 'bg-orange-500/10' : 'bg-[#1e293b]' }} flex items-center justify-center flex-shrink-0">
                            @if($instruction->is_task)
                                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @if($instruction->is_task)
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-orange-500/10 text-orange-400">TUGAS</span>
                                    @if($hasSubmitted)
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-green-500/10 text-green-400">DIKUMPULKAN</span>
                                    @endif
                                @else
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#1e293b] text-gray-400">MATERI</span>
                                @endif
                                <span class="text-xs text-gray-500">{{ $instruction->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <h3 class="text-lg font-bold text-white mb-2">{{ $instruction->title }}</h3>
                            
                            @if($instruction->description)
                                <p class="text-sm text-gray-400 line-clamp-2 mb-3">{{ Str::limit(strip_tags($instruction->description), 150) }}</p>
                            @endif

                            <div class="flex flex-wrap items-center gap-3">
                                @if($instruction->file_path)
                                    <a href="{{ asset('storage/' . $instruction->file_path) }}" target="_blank" download
                                        class="inline-flex items-center text-xs text-[#d4af37] hover:underline">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download Lampiran
                                    </a>
                                @endif
                                
                                @if($instruction->is_task && $instruction->deadline)
                                    <span class="inline-flex items-center text-xs {{ $instruction->isDeadlinePassed() ? 'text-red-400' : 'text-orange-400' }}">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $instruction->deadline->format('d M Y, H:i') }}
                                        @if($instruction->isDeadlinePassed())
                                            (Berakhir)
                                        @endif
                                    </span>
                                @endif

                                @if($hasSubmitted && $submission->grade !== null)
                                    <span class="inline-flex items-center text-xs {{ $submission->grade >= 70 ? 'text-green-400' : 'text-red-400' }}">
                                        Nilai: <strong class="ml-1">{{ $submission->grade }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Task Submission Form --}}
                    @if($instruction->is_task && !$hasSubmitted && !$instruction->isDeadlinePassed())
                        <div class="mt-6 pt-6 border-t border-[#1e293b]">
                            <form action="{{ route('student.instructions.submit', $instruction) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <h4 class="text-sm font-bold text-orange-400 uppercase tracking-widest">Kumpulkan Jawaban</h4>
                                
                                @if(in_array($instruction->allowed_response_type, ['file', 'both']))
                                    <div>
                                        <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Upload File</label>
                                        <input type="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.zip"
                                            class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-[#1e293b] file:text-gray-300 hover:file:bg-[#334155]">
                                    </div>
                                @endif

                                @if(in_array($instruction->allowed_response_type, ['text', 'both']))
                                    <div>
                                        <label class="block text-gray-400 text-xs font-bold uppercase mb-2">Jawaban Teks</label>
                                        <textarea name="text_response" rows="4"
                                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all resize-none"
                                            placeholder="Tulis jawaban Anda di sini..."></textarea>
                                    </div>
                                @endif

                                <button type="submit" 
                                    class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    KUMPULKAN
                                </button>
                            </form>
                        </div>
                    @elseif($instruction->is_task && $hasSubmitted)
                        <div class="mt-6 pt-6 border-t border-[#1e293b]">
                            <div class="flex items-center gap-3 p-4 bg-green-900/20 border border-green-500/30 rounded-xl">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-green-400 font-semibold text-sm">Tugas Sudah Dikumpulkan</p>
                                    <p class="text-xs text-gray-400">{{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                                </div>
                                @if($submission->file_path)
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" 
                                        class="ml-auto text-xs text-[#d4af37] hover:underline">Lihat File</a>
                                @endif
                            </div>
                        </div>
                    @elseif($instruction->is_task && $instruction->isDeadlinePassed() && !$hasSubmitted)
                        <div class="mt-6 pt-6 border-t border-[#1e293b]">
                            <div class="flex items-center gap-3 p-4 bg-red-900/20 border border-red-500/30 rounded-xl">
                                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-red-400 font-semibold text-sm">Batas Waktu Terlewat</p>
                                    <p class="text-xs text-gray-400">Anda tidak mengumpulkan tugas ini tepat waktu.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-12 text-center">
                <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Belum Ada Instruksi</h3>
                <p class="text-gray-400">Instruksi dari pengajar akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $instructions->links() }}
    </div>
</div>
@endsection
