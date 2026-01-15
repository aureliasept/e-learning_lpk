@extends('instructor.layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm mb-6">
        <a href="{{ route('instructor.dashboard') }}" class="text-gray-400 hover:text-[#d4af37] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">Profil Saya</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Pengaturan Profil</h1>
                <p class="text-sm text-gray-400">Kelola informasi akun Anda</p>
            </div>
        </div>
        <a href="{{ route('instructor.dashboard') }}" 
            class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] transition-all duration-200 text-sm font-bold">
            KEMBALI
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-900/30 border border-green-500/50 text-green-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-900/30 border border-red-500/50 text-red-300 p-4 rounded-xl mb-6">
            <p class="font-semibold text-sm mb-1">Terjadi kesalahan:</p>
            <ul class="text-sm space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Informasi Profil --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-8 shadow-xl">
            <h2 class="text-sm font-bold text-[#d4af37] uppercase tracking-widest mb-6">Informasi Profil</h2>

            <form action="{{ route('instructor.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] transition-all duration-200 text-sm font-bold">
                        SIMPAN PERUBAHAN
                    </button>
                </div>
            </form>
        </div>

        {{-- Ganti Password --}}
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-8 shadow-xl">
            <h2 class="text-sm font-bold text-[#d4af37] uppercase tracking-widest mb-6">Ganti Password</h2>

            <form action="{{ route('instructor.profile.password') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Password Lama</label>
                    <input type="password" name="current_password" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Password Baru</label>
                    <input type="password" name="password" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] transition-all duration-200 text-sm font-bold">
                        SIMPAN PASSWORD
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
