@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12" style="background-color: #0f172a; color: white;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Koreksi Tugas</div>
                <h1 class="text-3xl font-bold uppercase tracking-tight text-white mt-2">{{ $assignment->title }}</h1>
                @if($assignment->deadline)
                    <div class="text-sm text-gray-300 mt-2">
                        Deadline: <span class="font-bold" style="color:#c9a341;">{{ \Illuminate\Support\Carbon::parse($assignment->deadline)->format('d M Y H:i') }}</span>
                    </div>
                @endif
            </div>

            <a href="{{ route('instruktur.courses.show', $course->id) }}"
               class="px-4 py-2 rounded border border-gray-700 text-white font-bold uppercase tracking-widest hover:bg-white/5 transition no-underline"
               style="background-color: #0f172a;">
                Kembali
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-gray-700 shadow-xl p-4 mb-6" style="background-color: #1e293b;">
                <div class="text-sm text-white">
                    <span class="font-bold" style="color: #c9a341;">Sukses:</span>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="rounded-xl border border-gray-700 shadow-xl overflow-hidden" style="background-color: #1e293b;">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr style="background-color: #0f172a;">
                            <th class="text-left px-4 py-4 font-bold uppercase tracking-widest" style="color: #c9a341;">Nama Siswa</th>
                            <th class="text-left px-4 py-4 font-bold uppercase tracking-widest" style="color: #c9a341;">Status</th>
                            <th class="text-left px-4 py-4 font-bold uppercase tracking-widest" style="color: #c9a341;">File</th>
                            <th class="text-left px-4 py-4 font-bold uppercase tracking-widest" style="color: #c9a341;">Penilaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @if($rows->count() === 0)
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-sm text-gray-300">Belum ada siswa terdaftar di materi pembelajaran ini.</td>
                            </tr>
                        @else
                            @foreach($rows as $row)
                                @php
                                    $student = $row['student'];
                                    $submission = $row['submission'];
                                    $hasSubmitted = $submission !== null;
                                    $downloadUrl = $hasSubmitted ? asset('storage/' . ltrim($submission->file_path, '/')) : null;
                                @endphp

                                <tr>
                                    <td class="px-4 py-4 text-white font-bold">
                                        {{ $student->name ?? 'Siswa' }}
                                    </td>

                                    <td class="px-4 py-4">
                                        @if($hasSubmitted)
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-widest bg-green-500/15 text-green-200 border border-green-500/30">
                                                Sudah Kumpul
                                            </div>
                                            @if($submission->submitted_at)
                                                <div class="mt-2 text-xs text-gray-400">{{ $submission->submitted_at->format('d M Y H:i') }}</div>
                                            @endif
                                        @else
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-widest bg-red-500/15 text-red-200 border border-red-500/30">
                                                Belum
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4">
                                        @if($hasSubmitted)
                                            <a href="{{ $downloadUrl }}" target="_blank"
                                               class="px-3 py-2 rounded border border-gray-700 text-white font-bold uppercase tracking-widest hover:bg-white/5 transition no-underline"
                                               style="background-color: #0f172a;">
                                                Download
                                            </a>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4">
                                        @if(! $hasSubmitted)
                                            <div class="text-sm text-gray-400">Menunggu pengumpulan.</div>
                                        @else
                                            <form method="POST" action="{{ route('instruktur.assignments.submissions.grade', $submission->id) }}" class="grid grid-cols-1 lg:grid-cols-12 gap-2">
                                                @csrf
                                                @method('PUT')

                                                <div class="lg:col-span-3">
                                                    <input type="number" name="grade" min="0" max="100"
                                                           value="{{ old('grade', $submission->grade) }}"
                                                           placeholder="Nilai (0-100)"
                                                           class="w-full rounded bg-[#0f172a] text-white border-gray-700 focus:border-[#c9a341]" />
                                                </div>

                                                <div class="lg:col-span-7">
                                                    <textarea name="feedback" rows="1"
                                                              placeholder="Feedback"
                                                              class="w-full rounded bg-[#0f172a] text-white border-gray-700 focus:border-[#c9a341]">{{ old('feedback', $submission->feedback) }}</textarea>
                                                </div>

                                                <div class="lg:col-span-2 flex items-start justify-end">
                                                    <button type="submit"
                                                            class="px-4 py-2 rounded bg-[#c9a341] text-[#0f172a] font-bold uppercase tracking-widest">
                                                        Simpan
                                                    </button>
                                                </div>
                                            </form>
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
