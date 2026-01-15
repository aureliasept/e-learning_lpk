@extends('layouts.app')

@section('content')
<div class="py-12 min-h-screen" style="background-color: #0f172a; color: white;">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold uppercase tracking-tight text-white">{{ $title }}</h2>
                <p class="text-gray-400 text-sm mt-1">Daftar biodata peserta program {{ $type }}.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.students.index') }}" class="inline-flex items-center px-4 py-2 text-[#c9a341] font-bold text-sm uppercase tracking-widest hover:text-white transition-colors duration-200 no-underline">
                    &larr; Kembali
                </a>
                <a href="{{ route('admin.students.create') }}" class="inline-flex items-center justify-center px-5 py-3 bg-[#c9a341] border border-transparent rounded-xl font-bold text-sm uppercase tracking-widest text-[#0f172a] hover:bg-[#b08d35] focus:outline-none focus:ring-2 focus:ring-[#c9a341] focus:ring-offset-2 focus:ring-offset-[#1e293b] shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 no-underline">
                    + Tambah Peserta
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-white/10 overflow-hidden" style="background-color: #1e293b;">
            <table class="w-full text-left">
                <thead class="bg-[#0f172a] border-b border-white/10">
                    <tr>
                        <th class="px-6 py-5 font-bold text-[10px] uppercase tracking-widest text-[#c9a341]">Nama Lengkap</th>
                        <th class="px-6 py-5 font-bold text-[10px] uppercase tracking-widest text-[#c9a341]">NIS / ID</th>
                        <th class="px-6 py-5 font-bold text-[10px] uppercase tracking-widest text-[#c9a341]">Jenis Kelamin</th>
                        <th class="px-6 py-5 font-bold text-[10px] uppercase tracking-widest text-[#c9a341]">Alamat</th>
                        <th class="px-6 py-5 font-bold text-[10px] uppercase tracking-widest text-[#c9a341]">Kontak</th>
                        <th class="px-6 py-5 font-bold text-[10px] uppercase tracking-widest text-[#c9a341] text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($students as $s)
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-5">
                                <div class="font-bold text-sm text-white uppercase">{{ $s->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $s->user->email }}</div>
                            </td>
                            <td class="px-6 py-5 text-xs text-gray-400 font-mono">
                                {{ $s->nis }}
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-2 py-1 rounded text-[10px] uppercase border {{ $s->gender == 'L' ? 'border-blue-500 text-blue-500' : 'border-pink-500 text-pink-500' }}">
                                    {{ $s->gender == 'L' ? 'Laki-Laki' : 'Perempuan' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-xs text-gray-400 uppercase">
                                {{ Str::limit($s->address, 25) }}
                            </td>
                            <td class="px-6 py-5 text-xs text-gray-400">
                                {{ $s->phone }}
                            </td>
                            <td class="px-6 py-5 text-right flex justify-end gap-3">
                                <a href="{{ route('admin.students.edit', $s->user_id) }}" 
                                           class="text-[#c9a341] hover:text-white font-bold uppercase text-[10px] tracking-wider no-underline transition-colors duration-200 border border-[#c9a341] hover:bg-[#c9a341] px-3 py-1 rounded">
                                    Edit
                                </a>

                                <form action="{{ route('admin.students.destroy', $s->user_id) }}" method="POST" onsubmit="return confirm('Hapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                            <button class="text-red-500 hover:text-white font-bold uppercase text-[10px] tracking-wider transition-colors duration-200 border border-red-500 hover:bg-rose-600 px-3 py-1 rounded">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-xs uppercase tracking-widest">
                                Belum ada data peserta di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection