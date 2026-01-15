@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12" style="background-color: #0f172a; color: white;">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="rounded-xl border border-gray-700 shadow-xl p-8" style="background-color: #1e293b;">
            <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Nilai & Evaluasi</div>
            <h1 class="text-2xl font-extrabold text-white mt-2">Buat Quiz Baru</h1>

            <form method="POST" action="{{ route('instruktur.evaluations.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-white mb-2">Judul Quiz</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]" />
                </div>

                <div>
                    <label class="block text-sm font-bold text-white mb-2">Durasi (menit)</label>
                    <input type="number" name="duration" value="{{ old('duration', 30) }}" min="1" required
                           class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]" />
                </div>

                <div>
                    <label class="block text-sm font-bold text-white mb-2">Passing Grade (0-100)</label>
                    <input type="number" name="passing_grade" value="{{ old('passing_grade', 0) }}" min="0" max="100"
                           class="w-full rounded bg-[#0f172a] border-gray-700 text-white focus:border-[#c9a341]" />
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <a href="{{ route('instruktur.evaluations.index') }}"
                       class="px-4 py-2 rounded border border-gray-700 text-white font-bold uppercase tracking-widest hover:bg-white/5 transition no-underline"
                       style="background-color: #0f172a;">
                        Kembali
                    </a>
                    <button type="submit" class="px-5 py-2 rounded bg-[#c9a341] text-[#0f172a] font-bold uppercase tracking-widest">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
