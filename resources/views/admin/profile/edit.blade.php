@extends('admin.layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white tracking-wide uppercase">PENGATURAN PROFIL</h1>
            <p class="mt-1 text-sm text-gray-400">Kelola informasi akun admin Anda.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" 
            class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
            KEMBALI
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-900/40 border border-green-700 text-green-200 px-5 py-4 rounded-xl mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-900/30 border border-red-700 text-red-200 px-5 py-4 rounded-xl mb-6">
            <div class="font-bold mb-2">Terjadi kesalahan:</div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#0f172a] border border-[#1e293b] rounded-2xl p-8 shadow-xl">
            <h2 class="text-sm font-bold text-[#d4af37] uppercase tracking-widest mb-6">Informasi Profil</h2>

            <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-lg px-4 py-3 focus:ring-[#d4af37] focus:border-[#d4af37]">
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-lg px-4 py-3 focus:ring-[#d4af37] focus:border-[#d4af37]">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#d4af37] hover:bg-[#b8962e] text-[#0b1221] font-bold py-3 rounded-lg shadow-lg transition text-sm uppercase tracking-wider">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-[#0f172a] border border-[#1e293b] rounded-2xl p-8 shadow-xl">
            <h2 class="text-sm font-bold text-[#d4af37] uppercase tracking-widest mb-6">Ganti Password</h2>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Password Lama</label>
                    <input type="password" name="current_password" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-lg px-4 py-3 focus:ring-[#d4af37] focus:border-[#d4af37]">
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Password Baru</label>
                    <input type="password" name="password" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-lg px-4 py-3 focus:ring-[#d4af37] focus:border-[#d4af37]">
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-lg px-4 py-3 focus:ring-[#d4af37] focus:border-[#d4af37]">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-[#d4af37] hover:bg-[#b8962e] text-[#0b1221] font-bold py-3 rounded-lg shadow-lg transition text-sm uppercase tracking-wider">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
