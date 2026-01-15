<section>
    <header>
        <h2 class="text-lg font-bold text-white uppercase tracking-widest">
            Informasi Profil
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            Perbarui nama tampilan dan alamat email profil akun Anda.
        </p>
    </header>

    {{-- Form Update --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Input Nama --}}
        <div>
            <label class="block text-xs font-bold text-[#c9a341] uppercase tracking-widest mb-2" for="name">
                Nama Lengkap
            </label>
            <input id="name" name="name" type="text" class="w-full bg-[#0f172a] border border-gray-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#c9a341] focus:ring-1 focus:ring-[#c9a341] transition-all placeholder-gray-500" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @error('name')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Input Email --}}
        <div>
            <label class="block text-xs font-bold text-[#c9a341] uppercase tracking-widest mb-2" for="email">
                Email
            </label>
            <input id="email" name="email" type="email" class="w-full bg-[#0f172a] border border-gray-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#c9a341] focus:ring-1 focus:ring-[#c9a341] transition-all placeholder-gray-500" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-[#c9a341] hover:bg-[#b8933a] text-[#0f172a] font-bold py-2 px-6 rounded-lg uppercase tracking-widest transition-all shadow-lg hover:shadow-[#c9a341]/20">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400 font-bold"
                >
                    Berhasil disimpan.
                </p>
            @endif
        </div>
    </form>
</section>