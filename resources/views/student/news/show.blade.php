@extends('student.layouts.app')

@section('title', $news->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm mb-6">
        <a href="{{ route('student.dashboard') }}" class="text-gray-400 hover:text-[#d4af37] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('student.news.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Berita</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">{{ Str::limit($news->title, 30) }}</span>
    </nav>

    {{-- Main Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] rounded-2xl overflow-hidden border border-[#1e293b] shadow-xl">
        @if($news->image)
            <div class="w-full h-64 md:h-96 overflow-hidden bg-[#1e293b]">
                <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-6 md:p-10">
            <div class="flex items-center gap-3 mb-4">
                <span class="px-3 py-1 bg-[#d4af37] text-[#0b1221] text-[10px] font-bold rounded uppercase tracking-wider">Berita</span>
                <span class="text-xs text-gray-400">{{ ($news->published_at ?? $news->created_at)?->format('d M Y') ?? '-' }}</span>
            </div>

            <h1 class="text-2xl md:text-4xl font-bold text-white leading-tight">{{ $news->title }}</h1>

            <div class="mt-8 text-gray-300 leading-relaxed whitespace-pre-line">
                {{ $news->content }}
            </div>

            <div class="mt-10 pt-6 border-t border-[#1e293b]">
                <div class="text-xs text-gray-500 uppercase tracking-widest">Diposting oleh</div>
                <div class="text-white font-semibold mt-1">{{ $news->author->name ?? 'Admin' }}</div>
            </div>
        </div>
    </div>

    {{-- Back button --}}
    <div class="mt-6">
        <a href="{{ route('student.news.index') }}" 
            class="inline-flex items-center text-gray-400 hover:text-[#d4af37] transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Berita
        </a>
    </div>
</div>
@endsection
