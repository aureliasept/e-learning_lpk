@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12" style="background-color: #0f172a; color: white;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <div>
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Siswa</div>
                <h1 class="text-3xl font-bold uppercase tracking-tight text-white mt-2">Laporan Nilai</h1>
                <p class="text-gray-400 text-sm mt-2">Rekap nilai kuis dan tugas Anda.</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="px-5 py-2 rounded bg-[#c9a341] text-[#0f172a] font-bold uppercase tracking-widest no-underline">
                Kembali
            </a>
        </div>

        <div class="rounded-xl border border-gray-700 shadow-xl overflow-hidden" style="background-color: #1e293b;">
            <div class="p-6 border-b border-white/10">
                <h2 class="text-xl font-bold uppercase tracking-widest text-white border-l-4 pl-4" style="border-color: #c9a341;">Rekap Mata Pelajaran</h2>
            </div>

            <div class="p-6">
                @if(($rows ?? collect())->count() === 0)
                    <div class="text-sm text-gray-300">Belum ada nilai.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr style="background-color: #0f172a;">
                                    <th class="text-left px-4 py-3 font-bold uppercase tracking-widest" style="color:#c9a341;">No</th>
                                    <th class="text-left px-4 py-3 font-bold uppercase tracking-widest" style="color:#c9a341;">Mata Pelajaran</th>
                                    <th class="text-left px-4 py-3 font-bold uppercase tracking-widest" style="color:#c9a341;">Nilai Kuis</th>
                                    <th class="text-left px-4 py-3 font-bold uppercase tracking-widest" style="color:#c9a341;">Nilai Tugas</th>
                                    <th class="text-left px-4 py-3 font-bold uppercase tracking-widest" style="color:#c9a341;">Nilai Akhir</th>
                                    <th class="text-left px-4 py-3 font-bold uppercase tracking-widest" style="color:#c9a341;">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($rows as $i => $row)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-4 py-4 text-gray-200">{{ $i + 1 }}</td>
                                        <td class="px-4 py-4 text-white font-bold">{{ $row['courseTitle'] ?? '-' }}</td>
                                        <td class="px-4 py-4 text-gray-200">{{ $row['quizScore'] === null ? '-' : $row['quizScore'] }}</td>
                                        <td class="px-4 py-4 text-gray-200">{{ $row['assignmentScore'] === null ? '-' : $row['assignmentScore'] }}</td>
                                        <td class="px-4 py-4 text-white font-extrabold" style="color:#c9a341;">{{ $row['finalScore'] === null ? '-' : $row['finalScore'] }}</td>
                                        <td class="px-4 py-4">
                                            @php($status = $row['status'] ?? '-')
                                            @if($status === 'Lulus')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest" style="background-color: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.35); color: #6ee7b7;">Lulus</span>
                                            @elseif($status === 'Remedial')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest" style="background-color: rgba(244,63,94,0.12); border: 1px solid rgba(244,63,94,0.35); color: #fda4af;">Remedial</span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest" style="background-color: rgba(148,163,184,0.12); border: 1px solid rgba(148,163,184,0.35); color: #cbd5e1;">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
