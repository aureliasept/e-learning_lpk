<x-admin-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide uppercase">INSTRUKTUR</h1>
                <p class="mt-1 text-sm text-gray-400">Kelola data pengajar aktif</p>
            </div>
            <a href="{{ route('admin.instruktur.create') }}" class="bg-[#d4af37] hover:bg-[#b8962e] text-[#0b1221] font-bold py-2 px-6 rounded-lg shadow-lg text-sm transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                TAMBAH
            </a>
        </div>

        <div class="bg-[#0f172a] border border-[#1e293b] rounded-xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0b1221] text-[#d4af37] border-b border-[#1e293b] text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-bold">Nama Lengkap</th>
                            <th class="px-6 py-4 font-bold">Email / Kontak</th>
                            <th class="px-6 py-4 font-bold text-center">Status</th>
                            <th class="px-6 py-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1e293b] text-gray-300 text-sm">
                        @forelse($instructors ?? [] as $instructor)
                        <tr class="hover:bg-[#1e293b]/50 transition duration-150">
                            <td class="px-6 py-4 font-medium text-white">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-[#1e293b] flex items-center justify-center text-[#d4af37] font-bold mr-3 border border-[#d4af37]/30">
                                        {{ substr($instructor->name, 0, 1) }}
                                    </div>
                                    {{ $instructor->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ $instructor->email }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-green-900/30 text-green-400 border border-green-800 rounded text-xs font-bold">AKTIF</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-gray-400 hover:text-white mr-3 transition">Edit</a>
                                <button class="text-red-400 hover:text-red-300 transition">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data instruktur.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($instructors) && method_exists($instructors, 'links'))
            <div class="bg-[#0b1221] px-6 py-4 border-t border-[#1e293b]">
                {{ $instructors->links() }}
            </div>
            @endif
        </div>
    </div>
</x-admin-layout>