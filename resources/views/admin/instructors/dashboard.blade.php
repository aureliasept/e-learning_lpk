@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f172a] py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        {{-- Header Sambutan --}}
        <div class="mb-10 px-2">
            <h1 class="text-3xl font-bold text-white">Selamat Datang, <span class="text-[#c9a341]">{{ Auth::user()->name }}</span>!</h1>
            <p class="text-gray-400 mt-2">Panel Instruktur LPK Garuda Bakti Internasional.</p>
        </div>

        {{-- Grid Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            
            {{-- Card 1: Total Materi --}}
            <div class="bg-[#1e293b] border border-gray-700 rounded-xl p-6 shadow-lg relative overflow-hidden group hover:border-[#c9a341] transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Materi Diupload</p>
                        <h3 class="text-4xl font-bold text-white mt-2">{{ $totalModules }}</h3>
                    </div>
                    <div class="p-3 bg-[#0f172a] rounded-lg text-[#c9a341]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('instructor.modules.index') }}" class="text-[#c9a341] text-sm font-bold hover:underline flex items-center gap-1">
                        Kelola Materi &rarr;
                    </a>
                </div>
            </div>

            {{-- Card 2: Total Siswa --}}
            <div class="bg-[#1e293b] border border-gray-700 rounded-xl p-6 shadow-lg relative overflow-hidden group hover:border-[#c9a341] transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider">Total Siswa Aktif</p>
                        <h3 class="text-4xl font-bold text-white mt-2">{{ $totalStudents }}</h3>
                    </div>
                    <div class="p-3 bg-[#0f172a] rounded-lg text-[#c9a341]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-4">
                    <span class="text-gray-500 text-sm">Peserta didik terdaftar.</span>
                </div>
            </div>

        </div>

        {{-- Shortcut Menu --}}
        <div>
            <h2 class="text-xl font-bold text-white mb-6 border-l-4 border-[#c9a341] pl-4 uppercase tracking-wider">
                Aksi Cepat
            </h2>
            
            <a href="{{ route('instructor.modules.create') }}" class="block w-full md:w-1/3 bg-[#c9a341] hover:bg-[#b8933a] text-[#0f172a] font-bold py-4 px-6 rounded-xl shadow-lg transform hover:-translate-y-1 transition-all flex items-center justify-between group">
                <div class="flex items-center gap-4">
                    <div class="bg-[#0f172a]/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-[#0f172a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    </div>
                    <span>Upload Materi Baru</span>
                </div>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

    </div>
</div>
@endsection