@php
    $latestNews = \App\Models\News::latest()->take(3)->get();
@endphp

@extends('layouts.app')

@section('content')
<div style="background-color: #0f172a; min-height: 100vh; padding-top: 2.5rem; color: white;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-end mb-8 border-b border-gray-700 pb-4">
            <div>
                <h1 class="text-3xl font-bold uppercase tracking-tight text-white">Dashboard Admin</h1>
                <p class="text-gray-400 text-sm mt-1">Panel Kendali LPK Garuda Bakti Internasional</p>
            </div>
            <div class="px-4 py-2 rounded text-xs font-bold uppercase tracking-widest shadow-md" 
                 style="background-color: #1e293b; border: 1px solid #c9a341; color: #c9a341;">
                ● Sistem Online
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            
            <div class="p-6 rounded-xl relative overflow-hidden shadow-lg transition hover:-translate-y-1" 
                 style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold uppercase text-xs tracking-widest mb-2" style="color: #c9a341;">Instruktur</h3>
                        <p class="text-5xl font-extrabold text-white">{{ \App\Models\User::where('role', 'instruktur')->count() }}</p>
                    </div>
                    <div class="p-3 rounded-md" style="background-color: rgba(255,255,255,0.05);">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-700">
                    <a href="{{ route('admin.instruktur.index') }}" class="text-xs font-bold uppercase tracking-widest hover:text-[#c9a341] transition no-underline flex items-center gap-1">
                        Lihat Data <span class="text-base">&rsaquo;</span>
                    </a>
                </div>
            </div>

            <div class="p-6 rounded-xl relative overflow-hidden shadow-lg transition hover:-translate-y-1" 
                 style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold uppercase text-xs tracking-widest mb-2" style="color: #c9a341;">Siswa</h3>
                        <p class="text-5xl font-extrabold text-white">{{ \App\Models\Student::count() }}</p>
                    </div>
                    <div class="p-3 rounded-md" style="background-color: rgba(255,255,255,0.05);">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-700">
                    <a href="{{ route('admin.students.index') }}" class="text-xs font-bold uppercase tracking-widest hover:text-[#c9a341] transition no-underline flex items-center gap-1">
                        Lihat Data <span class="text-base">&rsaquo;</span>
                    </a>
                </div>
            </div>

            <div class="p-6 rounded-xl relative overflow-hidden shadow-lg transition flex flex-col justify-between" 
                 style="background-color: #1e293b; border: 1px solid #334155;">
                <div>
                    <h3 class="font-bold uppercase text-xs tracking-widest mb-2" style="color: #c9a341;">Aksi Cepat</h3>
                    <p class="text-sm text-gray-400">Bagikan pengumuman terbaru.</p>
                </div>
                <a href="{{ route('admin.news.create') }}" 
                   class="mt-4 block w-full text-center bg-[#c9a341] hover:bg-[#b08d35] text-[#0f172a] font-bold uppercase tracking-widest px-4 py-4 rounded shadow-md transition">
                    + Tulis Berita Baru
                </a>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-white uppercase tracking-widest border-l-4 border-[#c9a341] pl-4">
                    Berita & Informasi Terbaru
                </h3>
                <a href="{{ route('admin.news.index') }}" class="text-xs text-gray-400 hover:text-[#c9a341] uppercase font-bold tracking-widest">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($latestNews as $news)
                    <div class="rounded-xl overflow-hidden border border-gray-700 shadow-lg hover:border-[#c9a341] transition group" style="background-color: #1e293b;">
                        <div class="h-48 w-full bg-gray-800 relative overflow-hidden">
                            @php
                                $newsImage = $news->image ?? $news->image_path ?? null;
                            @endphp

                            @if($newsImage)
                                <img src="{{ asset('storage/' . $newsImage) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $news->title ?? $news->judul }}">
                            @else
                                <div class="flex items-center justify-center h-full" style="background-color: #0f172a;">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #c9a341;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-3 right-3 bg-black/70 px-3 py-1 rounded text-[10px] font-bold uppercase text-[#c9a341] backdrop-blur-sm border border-[#c9a341]/30">
                                {{ $news->created_at->format('d M Y') }}
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <h4 class="text-base font-bold text-white mb-3 line-clamp-2 leading-snug group-hover:text-[#c9a341] transition">
                                {{ $news->title ?? $news->judul }}
                            </h4>
                            <p class="text-xs text-gray-400 line-clamp-3 leading-relaxed">
                                {{ Str::limit(strip_tags($news->content ?? $news->deskripsi), 100) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 p-10 text-center border border-dashed border-gray-700 rounded-xl">
                        <p class="text-gray-500 text-sm">Belum ada berita yang dipublikasikan.</p>
                        <a href="{{ route('admin.news.create') }}" class="text-[#c9a341] text-sm font-bold hover:underline mt-2 inline-block">Mulai tulis berita sekarang</a>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection