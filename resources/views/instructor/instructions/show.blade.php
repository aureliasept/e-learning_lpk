@extends('instructor.layouts.app')

@section('title', $instruction->title)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

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
        <a href="{{ route('instructor.instructions.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Papan Instruksi</a>
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

    {{-- Main Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border {{ $instruction->is_task ? 'border-orange-500/30' : 'border-[#1e293b]' }} rounded-2xl shadow-xl overflow-hidden mb-6">
        <div class="p-6">
            {{-- Header --}}
            <div class="flex items-start justify-between gap-4 mb-6">
                <div class="flex items-start gap-4">
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
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            @if($instruction->is_task)
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-orange-500/10 text-orange-400">TUGAS</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#1e293b] text-gray-400">MATERI</span>
                            @endif
                            <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $instruction->class_type == 'reguler' ? 'bg-blue-500/10 text-blue-400' : ($instruction->class_type == 'karyawan' ? 'bg-purple-500/10 text-purple-400' : 'bg-green-500/10 text-green-400') }}">
                                {{ strtoupper($instruction->class_type) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $instruction->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <h1 class="text-xl font-bold text-white">{{ $instruction->title }}</h1>
                        <p class="text-sm text-gray-400 mt-1">
                            {{ $instruction->trainingBatch?->trainingYear?->name }} / {{ $instruction->trainingBatch?->name }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('instructor.instructions.edit', $instruction) }}" 
                        class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                        EDIT
                    </a>
                    <button type="button"
                        x-data
                        @click="$dispatch('confirm-delete', { 
                            url: '{{ route('instructor.instructions.destroy', $instruction) }}',
                            title: 'Hapus Instruksi',
                            message: 'Apakah Anda yakin ingin menghapus instruksi \'{{ $instruction->title }}\'?{{ $instruction->is_task && $instruction->submissions->count() > 0 ? ' Peringatan: ' . $instruction->submissions->count() . ' pengumpulan tugas juga akan terhapus.' : '' }}'
                        })"
                        class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-red-500 text-red-400 hover:text-white hover:bg-red-500 hover:border-red-500 transition-all duration-200 text-sm font-bold">
                        HAPUS
                    </button>
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
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm text-gray-400">Tipe Jawaban: 
                            @if($instruction->allowed_response_type == 'file') Upload File
                            @elseif($instruction->allowed_response_type == 'text') Teks
                            @else Keduanya
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
                <div class="flex items-center gap-3 p-4 bg-[#1e293b]/50 rounded-xl">
                    <svg class="w-8 h-8 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-white font-semibold">{{ basename($instruction->file_path) }}</p>
                        <p class="text-xs text-gray-400">Lampiran</p>
                    </div>
                    <a href="{{ asset('storage/' . $instruction->file_path) }}" target="_blank" download
                        class="inline-flex items-center px-4 py-2 rounded-xl bg-[#d4af37] text-[#0b1221] hover:bg-[#b8962e] transition-all text-sm font-bold">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Submissions Table (only for tasks) --}}
    @if($instruction->is_task)
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-[#1e293b]">
                <h2 class="text-lg font-bold text-white">Pengumpulan Tugas ({{ $instruction->submissions->count() }})</h2>
            </div>
            
            @if($instruction->submissions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#1e293b]/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Peserta</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Dikumpulkan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-400 uppercase">Jawaban</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase">Nilai</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1e293b]">
                            @foreach($instruction->submissions as $submission)
                                <tr class="hover:bg-[#1e293b]/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="text-white font-semibold">{{ $submission->student->user->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">{{ $submission->student->nis ?? '' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-gray-400">{{ $submission->submitted_at->format('d M Y, H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($submission->file_path)
                                            <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" 
                                                class="inline-flex items-center text-sm text-[#d4af37] hover:underline">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                </svg>
                                                Lihat File
                                            </a>
                                        @endif
                                        @if($submission->text_response)
                                            <button type="button" onclick="alert('{{ addslashes($submission->text_response) }}')" 
                                                class="inline-flex items-center text-sm text-gray-400 hover:text-[#d4af37] ml-2">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                Lihat Teks
                                            </button>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($submission->grade !== null)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $submission->grade >= 70 ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400' }}">
                                                {{ $submission->grade }}
                                            </span>
                                        @else
                                            <span class="text-gray-500 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4" x-data="{ showGrade: false }">
                                        <div class="flex justify-center">
                                            <button type="button" @click="showGrade = !showGrade"
                                                class="text-[#d4af37] hover:text-[#b8962e] text-sm font-bold">
                                                Beri Nilai
                                            </button>
                                        </div>
                                        <div x-show="showGrade" x-cloak class="mt-2">
                                            <form action="{{ route('instructor.instructions.grade', $submission) }}" method="POST" class="flex items-center justify-center gap-2">
                                                @csrf
                                                <input type="number" name="grade" value="{{ $submission->grade }}" min="0" max="100" required
                                                    class="w-20 bg-[#0b1221] border border-[#1e293b] text-white rounded-lg px-2 py-1 text-sm text-center">
                                                <button type="submit" class="px-3 py-1 rounded-lg bg-[#d4af37] text-[#0b1221] text-xs font-bold">OK</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-gray-400">Belum ada peserta yang mengumpulkan tugas.</p>
                </div>
            @endif
        </div>
    @endif

    {{-- Back button --}}
    <div class="mt-6">
        <a href="{{ route('instructor.instructions.index') }}" 
            class="inline-flex items-center text-gray-400 hover:text-[#d4af37] transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Papan Instruksi
        </a>
    </div>


</div>
@endsection
