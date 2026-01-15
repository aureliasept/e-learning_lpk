@extends('layouts.app')

@section('content')
<div class="py-12 bg-[#0f172a] min-h-screen text-white">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-10 px-4 sm:px-0">
            <a href="{{ route('admin.students.index') }}" class="text-yellow-500 hover:text-white transition text-xs font-black uppercase tracking-widest">&larr; KEMBALI</a>
            <h2 class="text-4xl font-black uppercase italic mt-4">{{ $title }}</h2>
        </div>

        <div class="bg-[#1e293b] rounded-[2rem] border border-white/10 overflow-hidden shadow-2xl mx-4 sm:mx-0">
            <table class="w-full text-left text-gray-300">
                <thead class="{{ $type == 'reguler' ? 'bg-blue-900/20 text-blue-300' : 'bg-green-900/20 text-green-300' }} text-[11px] uppercase font-black tracking-widest border-b border-white/5">
                    <tr>
                        <th class="px-8 py-6">Nama</th><th class="px-8 py-6">NIS</th><th class="px-8 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($students as $s)
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-8 py-6">
                            <div class="font-black text-white uppercase">{{ $s->user->name }}</div>
                            <div class="text-[10px] text-gray-500 font-bold uppercase italic">{{ $s->user->email }}</div>
                        </td>
                        <td class="px-8 py-6 font-mono font-bold">{{ $s->nis }}</td>
                        <td class="px-8 py-6 text-right">
                            <form action="{{ route('admin.students.destroy', $s->user->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="bg-red-500/10 text-red-500 border border-red-500/20 px-5 py-2 rounded-xl text-[10px] font-black uppercase italic">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-8 py-20 text-center font-black italic uppercase text-gray-600">Kosong</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection