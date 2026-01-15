<section>
    <header>
        <h2 class="text-lg font-bold text-white uppercase tracking-widest">
            Perbarui Password
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- Password Lama --}}
        <div>
            <label class="block text-xs font-bold text-[#c9a341] uppercase tracking-widest mb-2" for="current_password">
                Password Saat Ini
            </label>
            <input id="current_password" name="current_password" type="password" class="w-full bg-[#0f172a] border border-gray-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#c9a341] focus:ring-1 focus:ring-[#c9a341] transition-all placeholder-gray-500" autocomplete="current-password" />
            @error('current_password')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Baru --}}
        <div>
            <label class="block text-xs font-bold text-[#c9a341] uppercase tracking-widest mb-2" for="password">
                Password Baru
            </label>
            <input id="password" name="password" type="password" class="w-full bg-[#0f172a] border border-gray-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#c9a341] focus:ring-1 focus:ring-[#c9a341] transition-all placeholder-gray-500" autocomplete="new-password" />
            @error('password')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label class="block text-xs font-bold text-[#c9a341] uppercase tracking-widest mb-2" for="password_confirmation">
                Konfirmasi Password
            </label>
            <input id="password_confirmation" name="password_confirmation" type="password" class="w-full bg-[#0f172a] border border-gray-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-[#c9a341] focus:ring-1 focus:ring-[#c9a341] transition-all placeholder-gray-500" autocomplete="new-password" />
            @error('password_confirmation')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-[#c9a341] hover:bg-[#b8933a] text-[#0f172a] font-bold py-2 px-6 rounded-lg uppercase tracking-widest transition-all shadow-lg hover:shadow-[#c9a341]/20">
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400 font-bold"
                >
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>