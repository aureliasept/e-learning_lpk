@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12" style="background-color: #0f172a; color: white;" x-data="{ openEdit: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="rounded-xl border border-gray-700 shadow-xl p-8 mb-8" style="background-color: #0f172a;">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Instruktur</div>
                    <h1 class="text-3xl font-bold uppercase tracking-tight text-white mt-2">Jadwal Mengajar</h1>
                    <p class="text-gray-400 text-sm mt-2">Replikasi Jikanwari (jadwal fisik) LPK dengan slot waktu presisi.</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-gray-700 shadow-xl p-4 mb-6" style="background-color: #1e293b;">
                <div class="text-sm text-white">
                    <span class="font-bold" style="color: #c9a341;">Sukses:</span>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="rounded-xl border border-gray-700 shadow-xl overflow-hidden relative" style="background-color: #1e293b;">
            <button type="button"
                    @click="openEdit = true"
                    class="absolute top-4 right-4 h-9 w-9 rounded flex items-center justify-center border border-gray-700 hover:border-[#c9a341] hover:bg-white/5 transition"
                    style="background-color: #0f172a;">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="#c9a341" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 20h9" />
                    <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z" />
                </svg>
            </button>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr style="background-color: #0f172a;">
                            <th class="text-left px-4 py-4 font-bold uppercase tracking-widest" style="color: #c9a341;">Hari</th>
                            @foreach($slots as [$start, $end])
                                <th class="text-center px-4 py-4 font-bold uppercase tracking-widest whitespace-nowrap" style="color: #c9a341;">
                                    {{ substr($start, 0, 5) }} - {{ substr($end, 0, 5) }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($days as $dayId => $jp)
                            <tr class="border-t border-white/10">
                                <td class="px-4 py-4 font-extrabold whitespace-nowrap" style="background-color: #1e293b; color: white;">
                                    <div class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $jp }}</div>
                                    <div class="text-base">{{ $dayId }}</div>
                                </td>

                                @foreach($grid[$dayId] as $cell)
                                    <td class="px-4 py-4 text-center text-white hover:bg-white/5 transition">
                                        {{ $cell ?: '-' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 rounded-xl border border-gray-700 shadow-xl p-6" style="background-color: #1e293b;">
            <div class="text-sm text-gray-200">
                <span class="font-bold" style="color: #c9a341;">Info:</span>
                Doyoubi (Sabtu) & Nichiyoubi (Minggu) adalah <span class="font-bold" style="color: #c9a341;">YASUMI</span> (Libur).
                Istirahat 15 menit setiap 10:30 dan 15:00.
            </div>
        </div>

        <div x-show="openEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/60" @click="openEdit = false"></div>

            <div class="relative w-full max-w-lg rounded-xl border border-gray-700 shadow-2xl p-6" style="background-color: #1e293b;">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Edit Jadwal Mengajar</div>
                        <div class="text-xl font-extrabold text-white mt-2">Ubah Materi Pembelajaran per Slot</div>
                    </div>

                    <button type="button" @click="openEdit = false" class="h-9 w-9 rounded flex items-center justify-center border border-gray-700 hover:bg-white/5 transition" style="background-color: #0f172a;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="#c9a341" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('instruktur.schedules.update') }}" class="mt-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Hari</label>
                        <select name="day" class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]">
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Jam (Slot)</label>
                        <select name="slot" class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]">
                            @foreach($slots as $i => [$start, $end])
                                <option value="{{ $i + 1 }}">Slot {{ $i + 1 }} ({{ substr($start, 0, 5) }} - {{ substr($end, 0, 5) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Pilih Materi Pembelajaran</label>
                        <select name="course_id" class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]">
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">
                                    {{ $course->name ?? $course->nama ?? $course->title ?? 'Materi Pembelajaran' }}
                                </option>
                            @endforeach
                        </select>
                        @if($courses->count() === 0)
                            <div class="text-xs text-gray-400 mt-2">Belum ada Materi Pembelajaran yang terdaftar untuk instruktur ini.</div>
                        @endif
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-3">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 rounded border border-gray-700 text-white font-bold uppercase tracking-widest hover:bg-white/5 transition" style="background-color: #0f172a;">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded bg-[#c9a341] text-[#0f172a] font-bold uppercase tracking-widest">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
