@extends('admin.layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white tracking-wide uppercase">DASHBOARD ADMIN</h1>
            <p class="mt-1 text-sm text-gray-400">Panel Kendali LPK Garuda Bakti Internasional</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            
            <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-6 shadow-lg hover:border-[#d4af37]/50 transition duration-300 group">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-[10px] font-bold text-[#d4af37] uppercase tracking-widest mb-1">Total Instruktur</h3>
                        <div class="text-4xl font-bold text-white">{{ $stats['instructors'] ?? 0 }}</div>
                    </div>
                    <div class="p-2 bg-[#1e293b] rounded-lg text-gray-400 group-hover:text-[#d4af37] transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="border-t border-[#1e293b] pt-4 mt-2">
                    <a href="{{ route('admin.instructors.index') }}" class="text-xs font-bold text-gray-400 hover:text-[#d4af37] flex items-center transition">
                        LIHAT DATA <span class="ml-1">&rarr;</span>
                    </a>
                </div>
            </div>

            <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-6 shadow-lg hover:border-[#d4af37]/50 transition duration-300 group">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-[10px] font-bold text-[#d4af37] uppercase tracking-widest mb-1">Total Siswa</h3>
                        <div class="text-4xl font-bold text-white">{{ $stats['students'] ?? 0 }}</div>
                    </div>
                    <div class="p-2 bg-[#1e293b] rounded-lg text-gray-400 group-hover:text-[#d4af37] transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="border-t border-[#1e293b] pt-4 mt-2">
                    <a href="{{ route('admin.classes.reguler') }}" class="text-xs font-bold text-gray-400 hover:text-[#d4af37] flex items-center transition">
                        LIHAT DATA <span class="ml-1">&rarr;</span>
                    </a>
                </div>
            </div>

            <div class="bg-[#0f172a] rounded-xl border border-[#1e293b] p-6 shadow-lg flex flex-col justify-between hover:border-[#d4af37]/50 transition duration-300">
                <div>
                    <h3 class="text-[10px] font-bold text-[#d4af37] uppercase tracking-widest mb-2">AKSI CEPAT</h3>
                    <p class="text-sm text-gray-400 mb-4">Buat pengumuman atau berita baru untuk siswa & instruktur.</p>
                </div>
                <a href="{{ route('admin.news.create') }}" class="block w-full text-center bg-[#d4af37] hover:bg-[#b8962e] text-[#0b1221] font-bold py-3 px-4 rounded-lg uppercase text-xs tracking-wider shadow-md transition transform active:scale-[0.98]">
                    + Tulis Berita
                </a>
            </div>
        </div>

        <div class="bg-[#0f172a] border border-[#1e293b] rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-[#1e293b] flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-[#d4af37] uppercase tracking-widest">Berita Publish Terbaru</h2>
                    <p class="text-xs text-gray-400 mt-1">Yang tampil ke peserta hanya yang berstatus Publish.</p>
                </div>
                <a href="{{ route('admin.news.index') }}" class="text-xs font-bold text-gray-400 hover:text-[#d4af37] transition">LIHAT SEMUA &rarr;</a>
            </div>

            <div class="divide-y divide-[#1e293b]">
                @forelse(($latestPublishedNews ?? []) as $item)
                    <div class="px-6 py-4 hover:bg-[#1e293b]/30 transition">
                        <div class="flex items-start justify-between gap-4">
                            <a href="{{ route('admin.news.show', $item->id) }}" class="flex items-start gap-4 min-w-0">
                                <div class="w-20 h-14 rounded-lg overflow-hidden border border-[#1e293b] bg-[#0b1221] shrink-0">
                                    @if($item->image)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-20 h-14 object-cover">
                                    @else
                                        <div class="w-20 h-14 flex items-center justify-center text-[#d4af37] text-xs font-bold">GBI</div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <div class="text-white font-semibold hover:text-[#d4af37] transition truncate">{{ $item->title }}</div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ ($item->published_at ?? $item->created_at)?->format('d/m/Y') ?? '-' }}
                                    </div>
                                </div>
                            </a>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest" style="background-color: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.35); color: #6ee7b7;">Publish</span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-6 text-sm text-gray-500 italic">Belum ada berita yang dipublish.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection