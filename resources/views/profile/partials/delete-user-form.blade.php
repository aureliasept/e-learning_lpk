<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-red-500 uppercase tracking-widest">
            Hapus Akun
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen.
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg uppercase tracking-widest transition-all"
    >
        Hapus Akun Ini
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-[#1e293b] border border-gray-700 text-white">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-white uppercase tracking-widest">
                Apakah Anda yakin?
            </h2>

            <p class="mt-1 text-sm text-gray-400">
                Setelah akun dihapus, semua data tidak dapat dikembalikan. Masukkan password untuk konfirmasi.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full bg-[#0f172a] border border-gray-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    placeholder="Password Anda"
                />
                @error('password', 'userDeletion')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" x-on:click="$dispatch('close')" class="mr-3 px-4 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 font-semibold">
                    Batal
                </button>

                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg uppercase tracking-widest">
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>