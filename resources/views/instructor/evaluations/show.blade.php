@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12" style="background-color: #0f172a; color: white;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between gap-4 mb-8">
            <div>
                <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Quiz Detail</div>
                <h1 class="text-3xl font-bold uppercase tracking-tight text-white mt-2">{{ $quiz->title }}</h1>
                <div class="text-sm text-gray-300 mt-2">
                    Durasi: <span class="font-bold" style="color:#c9a341;">{{ $quiz->duration }}</span> menit,
                    Passing Grade: <span class="font-bold" style="color:#c9a341;">{{ $quiz->passing_grade }}</span>
                </div>
            </div>
            <a href="{{ route('instruktur.evaluations.index') }}"
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-xl border border-gray-700 shadow-xl p-6" style="background-color: #1e293b;">
                <div class="text-sm font-extrabold uppercase tracking-widest" style="color: #c9a341;">Tambah Soal Pilihan Ganda</div>

                <form method="POST" action="{{ route('instruktur.evaluations.questions.store', $quiz->id) }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Pertanyaan</label>
                        <textarea name="question_text" rows="3" required
                                  class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Opsi A</label>
                            <input type="text" name="option_a" required class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Opsi B</label>
                            <input type="text" name="option_b" required class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Opsi C</label>
                            <input type="text" name="option_c" required class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-white mb-2">Opsi D</label>
                            <input type="text" name="option_d" required class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-white mb-2">Kunci Jawaban</label>
                        <select name="correct_option" class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]">
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                            <option value="d">D</option>
                        </select>
                    </div>

                    <div class="pt-2 flex items-center justify-end">
                        <button type="submit" class="px-5 py-2 rounded bg-[#c9a341] text-[#0f172a] font-bold uppercase tracking-widest">
                            Simpan Soal
                        </button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl border border-gray-700 shadow-xl p-6" style="background-color: #1e293b;">
                <div class="text-sm font-extrabold uppercase tracking-widest" style="color: #c9a341;">Daftar Soal</div>

                <div class="mt-5 space-y-4">
                    @if($quiz->questions->count() === 0)
                        <div class="text-sm text-gray-400">Belum ada soal.</div>
                    @else
                        @foreach($quiz->questions as $q)
                            <div class="p-4 rounded border border-white/10" style="background-color: #0f172a;">
                                <div class="text-white font-bold">{{ $loop->iteration }}. {{ $q->question_text }}</div>
                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-200">
                                    <div>A. {{ $q->option_a }}</div>
                                    <div>B. {{ $q->option_b }}</div>
                                    <div>C. {{ $q->option_c }}</div>
                                    <div>D. {{ $q->option_d }}</div>
                                </div>
                                <div class="mt-3 text-xs font-bold uppercase tracking-widest" style="color:#c9a341;">Kunci: {{ strtoupper($q->correct_option) }}</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
