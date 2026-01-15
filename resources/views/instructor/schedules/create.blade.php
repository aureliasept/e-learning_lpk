@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 bg-[#0f172a] text-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6">
            <h2 class="text-2xl font-bold uppercase tracking-widest text-white">Tambah Jadwal Mengajar</h2>
            <a href="{{ route('instructor.dashboard') }}" class="text-sm text-[#c9a341] hover:underline">&larr; Kembali ke Dashboard</a>
        </div>

        <div class="bg-[#1e293b] border border-gray-700 rounded-xl p-8 shadow-xl">
            <form action="{{ route('instructor.schedules.store') }}" method="POST">
                @csrf
                
                {{-- Hari --}}
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Hari</label>
                    <select name="day" class="w-full bg-[#0f172a] border border-gray-600 rounded p-3 text-white focus:border-[#c9a341] outline-none">
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    {{-- Jam Mulai --}}
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Jam Mulai</label>
                        <input type="time" name="start_time" required class="w-full bg-[#0f172a] border border-gray-600 rounded p-3 text-white focus:border-[#c9a341] outline-none">
                    </div>
                    {{-- Jam Selesai --}}
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Jam Selesai</label>
                        <input type="time" name="end_time" required class="w-full bg-[#0f172a] border border-gray-600 rounded p-3 text-white focus:border-[#c9a341] outline-none">
                    </div>
                </div>

                {{-- Mata Pelajaran --}}
                <div class="mb-4">
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Mata Pelajaran (Modul/Bab)</label>
                    <input type="text" name="subject" placeholder="Contoh: Bahasa Inggris - Bab 1" required class="w-full bg-[#0f172a] border border-gray-600 rounded p-3 text-white focus:border-[#c9a341] outline-none">
                </div>

                {{-- Kelas & Ruangan --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Nama Kelas</label>
                        <input type="text" name="class_name" placeholder="Contoh: Reguler Pagi" required class="w-full bg-[#0f172a] border border-gray-600 rounded p-3 text-white focus:border-[#c9a341] outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Ruangan (Opsional)</label>
                        <input type="text" name="room" placeholder="Contoh: Lab 1" class="w-full bg-[#0f172a] border border-gray-600 rounded p-3 text-white focus:border-[#c9a341] outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#c9a341] text-[#0f172a] font-bold py-4 rounded hover:bg-[#b8933a] transition-colors uppercase tracking-widest">
                    Simpan Jadwal
                </button>
            </form>
        </div>
    </div>
</div>
@endsection