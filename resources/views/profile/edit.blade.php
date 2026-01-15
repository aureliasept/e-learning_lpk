@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f172a] py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        {{-- Header Page --}}
        <div class="flex items-center justify-between px-2">
            <div>
                <h2 class="text-3xl font-bold text-white tracking-tight">PENGATURAN PROFIL</h2>
                <p class="text-[#c9a341] text-sm font-semibold tracking-widest uppercase mt-1">Kelola Akun & Keamanan</p>
            </div>
        </div>

        {{-- Card 1: Update Informasi Profil --}}
        <div class="p-4 sm:p-8 bg-[#1e293b] border border-gray-700 shadow-2xl sm:rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Card 2: Update Password --}}
        <div class="p-4 sm:p-8 bg-[#1e293b] border border-gray-700 shadow-2xl sm:rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Card 3: Hapus Akun --}}
        <div class="p-4 sm:p-8 bg-[#1e293b] border border-red-900/50 shadow-2xl sm:rounded-xl">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection