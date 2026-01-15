@extends('layouts.app')

@section('content')
<div class="py-12 bg-lpk-navy min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6 px-4 sm:px-0 flex justify-between items-center">
            <div>
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-lpk-gold text-sm font-medium mb-1 inline-block transition">&larr; Kembali ke Dashboard</a>
                <h2 class="text-2xl font-bold text-white">Daftar Semua Tugas</h2>
                <p class="text-gray-400 text-sm">Memantau tugas-tugas yang baru diunggah di semua modul.</p>
            </div>
        </div>

        <div class="bg-lpk-navy-light overflow-hidden shadow-xl sm:rounded-xl border border-lpk-gold/20">
            <div class="p-1">
                <table class="w-full text-sm text-left text-gray-300">
                    <thead class="text-xs text-lpk-gold uppercase bg-lpk-navy border-b border-lpk-gold/20">
                        <tr>
                            <th class="px-6 py-4 font-bold tracking-wider">Judul Tugas</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Lokasi (Modul / Bab)</th>
                            <th class="px-6 py-4 font-bold tracking-wider">Deadline</th>
                            <th class="px-6 py-4 font-bold tracking-wider text-center">File Soal</th>
                            <th class="px-6 py-4 font-bold tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($assignments as $assignment)
                        <tr class="hover:bg-lpk-navy transition duration-200">
                            {{-- Judul --}}
                            <td class="px-6 py-4 font-bold text-white">
                                {{ $assignment->title }}
                                <div class="text-xs text-gray-500 mt-1 font-normal">Dibuat: {{ $assignment->created_at->diffForHumans() }}</div>
                            </td>
                            {{-- Lokasi --}}
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-lpk-gold font-bold text-xs uppercase">{{ $assignment->material->course->title ?? 'Modul Terhapus' }}</span>
                                    <span class="text-gray-400 text-xs">{{ $assignment->material->title ?? 'Bab Terhapus' }}</span>
                                </div>
                            </td>
                            {{-- Deadline --}}
                            <td class="px-6 py-4">
                                @if($assignment->deadline)
                                    <span class="px-2 py-1 rounded text-xs font-bold border {{ \Carbon\Carbon::parse($assignment->deadline)->isPast() ? 'border-red-500 bg-red-900 text-red-300' : 'border-blue-500 bg-blue-900 text-blue-300' }}">
                                        {{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y') }}
                                    </span>
                                @else <span class="text-gray-500 italic text-xs">-</span> @endif
                            </td>
                            {{-- File --}}
                            <td class="px-6 py-4 text-center">
                                @if($assignment->file_path)
                                    <a href="{{ Storage::url($assignment->file_path) }}" target="_blank" class="text-blue-400 hover:text-white underline text-xs">Unduh</a>
                                @else <span class="text-gray-600 text-xs">-</span> @endif
                            </td>
                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-white border border-red-500/30 hover:bg-red-600 px-3 py-1 rounded text-xs transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">Belum ada tugas yang dibuat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-700 bg-lpk-navy">{{ $assignments->links() }}</div>
        </div>
    </div>
</div>
@endsection