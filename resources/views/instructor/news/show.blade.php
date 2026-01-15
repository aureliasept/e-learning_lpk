@extends('instructor.layouts.app')

@section('title', $news->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm mb-6">
        <a href="{{ route('instructor.dashboard') }}" class="text-gray-400 hover:text-[#d4af37] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-400">Berita</span>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium line-clamp-1">{{ Str::limit($news->title, 30) }}</span>
    </nav>

    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('instructor.dashboard') }}" 
           class="inline-flex items-center gap-2 text-gray-400 hover:text-[#d4af37] transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Article Card --}}
    <article class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        {{-- Featured Image --}}
        @if($news->image_url)
            <div class="aspect-video bg-[#1e293b] overflow-hidden">
                <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-8">
            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-4 mb-4">
                <span class="px-3 py-1 bg-[#d4af37]/10 text-[#d4af37] border border-[#d4af37]/30 rounded-full text-xs font-bold uppercase tracking-wider">
                    Berita
                </span>
                @if($news->published_at)
                    <span class="text-gray-400 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $news->published_at->format('d F Y') }}
                    </span>
                @endif
                @if($news->author)
                    <span class="text-gray-400 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $news->author->name }}
                    </span>
                @endif
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6 leading-tight">
                {{ $news->title }}
            </h1>

            {{-- Content --}}
            <div class="prose prose-invert prose-gold max-w-none">
                <div class="text-gray-300 leading-relaxed space-y-4">
                    {!! nl2br(e($news->content)) !!}
                </div>
            </div>
        </div>
    </article>

</div>
@endsection
