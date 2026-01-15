@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 bg-[#0f172a] text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header Modul --}}
        <div class="flex justify-between items-start mb-8 border-b border-gray-700 pb-6">
            <div>
                <a href="{{ route('instructor.modules.index') }}" class="text-xs text-gray-400 hover:text-[#c9a341] mb-2 block font-bold uppercase tracking-wider">&larr; Kembali ke Daftar Modul</a>
                <h1 class="text-3xl font-bold text-white">{{ $module->title }}</h1>
                <p class="text-gray-400 mt-1 text-sm">{{ $module->description }}</p>
            </div>
            {{-- Tombol Tambah Bab --}}
            <a href="{{ route('instructor.chapters.create', ['module_id' => $module->id]) }}" class="bg-[#c9a341] text-[#0f172a] font-bold px-4 py-3 rounded hover:bg-[#b8933a] shadow-lg transition-all flex items-center gap-2 uppercase text-xs tracking-widest">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Bab
            </a>
        </div>

        {{-- List Bab / Chapters --}}
        <div class="space-y-4">
            @forelse($module->chapters as $index => $chapter)
            <div class="bg-[#1e293b] border border-gray-700 rounded-xl p-6 flex justify-between items-start hover:border-gray-500 transition-colors group">
                <div class="flex gap-4">
                    {{-- Nomor Bab --}}
                    <div class="flex-shrink-0 w-10 h-10 bg-[#0f172a] rounded-full flex items-center justify-center font-bold text-[#c9a341] border border-gray-700 group-hover:border-[#c9a341] transition-colors">
                        {{ $index + 1 }}
                    </div>
                    
                    {{-- Info Bab --}}
                    <div>
                        <h3 class="text-lg font-bold text-white group-hover:text-[#c9a341] transition-colors">{{ $chapter->title }}</h3>
                        
                        {{-- Badges Materi --}}
                        <div class="flex gap-2 mt-2">
                            @if($chapter->file_path)
                                <span class="bg-green-900/50 text-green-300 border border-green-800 text-[10px] px-2 py-0.5 rounded uppercase font-bold tracking-wide">PDF / File</span>
                            @endif
                            @if($chapter->video_url)
                                <span class="bg-red-900/50 text-red-300 border border-red-800 text-[10px] px-2 py-0.5 rounded uppercase font-bold tracking-wide">Video</span>
                            @endif
                            @if($chapter->content)
                                <span class="bg-blue-900/50 text-blue-300 border border-blue-800 text-[10px] px-2 py-0.5 rounded uppercase font-bold tracking-wide">Teks</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="flex items-center gap-3">
                    <form action="{{ route('instructor.chapters.destroy', $chapter->id) }}" method="POST" onsubmit="return confirm('Yakin hapus Bab ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-600 hover:text-red-500 transition-colors p-2" title="Hapus Bab">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-12 border-2 border-dashed border-gray-700 rounded-xl bg-[#0f172a]/50">
                <p class="text-gray-400 font-bold">Belum ada Bab Materi</p>
                <p class="text-xs text-gray-500 mt-1">Klik "Tambah Bab" untuk mulai mengisi materi.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection