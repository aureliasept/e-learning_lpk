@extends('student.layouts.app')

@section('title', 'Berita & Info')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
            <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-white tracking-wide">Berita & Info</h1>
            <p class="text-sm text-gray-400">Informasi terbaru dari LPK Garuda Bakti Internasional</p>
        </div>
    </div>

    {{-- News Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($news as $item)
            <a href="{{ route('student.news.show', $item->slug) }}" class="group bg-gradient-to-b from-[#0f172a] to-[#0b1221] rounded-2xl overflow-hidden border border-[#1e293b] hover:border-[#d4af37]/50 transition-all">
                <div class="h-44 bg-[#1e293b] overflow-hidden">
                    @if($item->image)
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-44 object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-44 flex items-center justify-center text-[#d4af37] font-bold text-2xl">GBI</div>
                    @endif
                </div>
                <div class="p-5">
                    <div class="text-xs text-gray-500 mb-2">{{ ($item->published_at ?? $item->created_at)?->format('d M Y') ?? '-' }}</div>
                    <h2 class="text-white font-bold leading-snug group-hover:text-[#d4af37] transition line-clamp-2">{{ $item->title }}</h2>
                    <p class="text-sm text-gray-400 mt-2 line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($item->content), 120) }}</p>
                    <div class="mt-4 text-sm font-bold text-[#d4af37]">Baca Selengkapnya →</div>
                </div>
            </a>
        @empty
            <div class="col-span-3 bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-12 text-center">
                <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <p class="text-gray-400">Belum ada berita yang dipublish.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $news->links() }}
    </div>
</div>
@endsection
