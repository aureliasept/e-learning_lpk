@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12" style="background-color: #0f172a; color: white;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Rekap Nilai Quiz</div>
                <h1 class="text-3xl font-bold uppercase tracking-tight text-white mt-2">{{ $quiz->title }}</h1>
            </div>

            <a href="{{ route('instruktur.evaluations.index') }}"
               class="px-4 py-2 rounded border border-gray-700 text-white font-bold uppercase tracking-widest hover:bg-white/5 transition no-underline"
               style="background-color: #0f172a;">
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <div class="rounded-xl border border-gray-700 shadow-xl p-5" style="background-color: #1e293b;">
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Rata-rata Nilai</div>
                <div class="mt-2 text-3xl font-extrabold" style="color:#c9a341;">{{ $averageScore }}</div>
            </div>

            <div class="rounded-xl border border-gray-700 shadow-xl p-5" style="background-color: #1e293b;">
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Total Peserta</div>
                <div class="mt-2 text-3xl font-extrabold" style="color:#c9a341;">{{ $totalParticipants }}</div>
            </div>

            <div class="rounded-xl border border-gray-700 shadow-xl p-5" style="background-color: #1e293b;">
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Passing Grade</div>
                <div class="mt-2 text-3xl font-extrabold" style="color:#c9a341;">{{ $quiz->passing_grade }}</div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-700 shadow-xl overflow-hidden" style="background-color: #1e293b;">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr style="background-color: #0f172a;">
                            <th class="text-left px-4 py-4 font-bold uppercase tracking-widest" style="color: #c9a341;">Nama Siswa</th>
                            <th class="text-left px-4 py-4 font-bold uppercase tracking-widest" style="color: #c9a341;">Tanggal Tes</th>
                            <th class="text-left px-4 py-4 font-bold uppercase tracking-widest" style="color: #c9a341;">Nilai Akhir</th>
                            <th class="text-left px-4 py-4 font-bold uppercase tracking-widest" style="color: #c9a341;">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @if($grades->count() === 0)
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-sm text-gray-300">Belum ada peserta yang mengerjakan quiz ini.</td>
                            </tr>
                        @else
                            @foreach($grades as $grade)
                                @php
                                    $score = (int) ($grade->score ?? 0);
                                    $isPass = $score >= (int) ($quiz->passing_grade ?? 0);
                                    $takenAt = $grade->taken_at ?? $grade->created_at;
                                @endphp

                                <tr>
                                    <td class="px-4 py-4 text-white font-bold">
                                        {{ $grade->user->name ?? 'Siswa' }}
                                    </td>
                                    <td class="px-4 py-4 text-gray-200">
                                        {{ $takenAt ? $takenAt->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="font-extrabold" style="color:#c9a341;">{{ $score }}</span>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($isPass)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-widest bg-green-500/15 text-green-200 border border-green-500/30">
                                                LULUS
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-widest bg-red-500/15 text-red-200 border border-red-500/30">
                                                REMEDIAL
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
