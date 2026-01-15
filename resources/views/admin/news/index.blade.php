@extends('admin.layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-sm mb-6">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-[#d4af37] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </a>
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-[#d4af37] font-medium">Berita & Info</span>
        </nav>

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                    <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white tracking-wide">Berita & Info</h1>
                    <p class="text-sm text-gray-400">Kelola pengumuman untuk siswa</p>
                </div>
            </div>
            <a href="{{ route('admin.news.create') }}" 
                class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                TULIS BERITA
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

        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden shadow-2xl" x-data>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#0b1221]/80 border-b border-[#1e293b]">
                            <th class="px-6 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider w-1/2">Judul</th>
                            <th class="px-6 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider text-center">Status</th>
                            <th class="px-6 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-[#d4af37] text-xs font-bold uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1e293b] text-gray-300 text-sm">
                        @forelse($news as $item)
                        <tr class="hover:bg-[#1e293b]/30 transition duration-150">
                            <td class="px-6 py-4 font-medium text-white">
                                <a href="{{ route('admin.news.show', $item->id) }}" class="hover:text-[#d4af37] transition">
                                    {{ $item->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(($item->status ?? 'draft') === 'publish')
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-green-500/10 text-green-400 border border-green-500/30 text-xs font-semibold">
                                        Publish
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-gray-500/10 text-gray-400 border border-gray-500/30 text-xs font-semibold">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-400">
                                {{ (($item->status ?? 'draft') === 'publish' ? $item->published_at : $item->created_at)?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.news.show', $item->id) }}" 
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#1e293b] text-gray-400 hover:text-white hover:bg-[#334155] transition-all duration-200" 
                                        title="Lihat">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.news.edit', $item->id) }}" 
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#1e293b] text-gray-400 hover:text-[#d4af37] hover:bg-[#334155] transition-all duration-200" 
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button type="button" 
                                        @click="$dispatch('confirm-delete', { 
                                            url: '{{ route('admin.news.destroy', $item->id) }}',
                                            title: 'Hapus Berita',
                                            message: 'Apakah Anda yakin ingin menghapus berita \'{{ $item->title }}\'? Tindakan ini tidak dapat dibatalkan.'
                                        })"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#1e293b] text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-all duration-200"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center gap-4">
                                    <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-gray-400 font-medium">Belum ada berita</p>
                                        <p class="text-gray-500 text-sm mt-1">Klik tombol "Tulis Berita" untuk menambah berita baru</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($news->hasPages())
            <div class="bg-[#0b1221]/50 px-6 py-4 border-t border-[#1e293b]">
                {{ $news->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection