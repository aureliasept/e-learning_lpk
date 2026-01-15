@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-20" x-data>

    {{-- Breadcrumbs --}}
    <nav class="flex items-center flex-wrap gap-2 text-sm mb-8">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-[#d4af37] transition-colors flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>
        <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('admin.news.index') }}" class="text-gray-400 hover:text-[#d4af37] transition-colors">Berita & Info</a>
        <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium truncate max-w-[200px] sm:max-w-none">{{ Str::limit($news->title, 40) }}</span>
    </nav>

    {{-- Main Article Card --}}
    <article class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        
        {{-- Hero Image Section --}}
        <div class="relative w-full aspect-[16/9] md:aspect-[21/9] overflow-hidden">
            @if($news->image)
                <img src="{{ $news->image_url }}" 
                     alt="{{ $news->title }}" 
                     class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                {{-- Gradient Overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/60 to-transparent"></div>
            @else
                {{-- Fallback Gradient --}}
                <div class="w-full h-full bg-gradient-to-br from-[#0f172a] via-[#1e293b] to-[#d4af37]/30 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-20 h-20 text-[#d4af37]/40 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-transparent to-transparent"></div>
            @endif

            {{-- Category Badge on Image --}}
            <div class="absolute top-4 left-4 sm:top-6 sm:left-6">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#d4af37] text-[#0b1221] shadow-lg shadow-[#d4af37]/30">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    {{ $news->category ?? 'Berita Sekolah' }}
                </span>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="p-6 md:p-10 lg:p-12 -mt-16 md:-mt-24 relative z-10">
            
            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white leading-tight mb-6 drop-shadow-lg">
                {{ $news->title }}
            </h1>

            {{-- Meta Data Row --}}
            <div class="flex flex-wrap items-center gap-4 sm:gap-6 mb-8 pb-6 border-b border-[#1e293b]/80">
                {{-- Date --}}
                <div class="flex items-center gap-2 text-gray-400">
                    <div class="w-8 h-8 rounded-full bg-[#1e293b] flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-medium">Tanggal</p>
                        <p class="text-sm font-medium text-gray-300">{{ ($news->published_at ?? $news->created_at)?->translatedFormat('d F Y') ?? '-' }}</p>
                    </div>
                </div>

                {{-- Author --}}
                <div class="flex items-center gap-2 text-gray-400">
                    <div class="w-8 h-8 rounded-full bg-[#1e293b] flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-medium">Penulis</p>
                        <p class="text-sm font-medium text-gray-300">{{ $news->author->name ?? 'Admin' }}</p>
                    </div>
                </div>

                {{-- Reading Time (Optional Enhancement) --}}
                <div class="flex items-center gap-2 text-gray-400">
                    <div class="w-8 h-8 rounded-full bg-[#1e293b] flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-medium">Waktu Baca</p>
                        <p class="text-sm font-medium text-gray-300">{{ max(1, ceil(str_word_count(strip_tags($news->content)) / 200)) }} menit</p>
                    </div>
                </div>
            </div>

            {{-- Article Body with Prose --}}
            <div class="prose prose-lg prose-invert max-w-none
                        prose-headings:text-white prose-headings:font-bold
                        prose-p:text-gray-300 prose-p:leading-relaxed
                        prose-a:text-[#d4af37] prose-a:no-underline hover:prose-a:underline
                        prose-strong:text-white prose-strong:font-semibold
                        prose-ul:text-gray-300 prose-ol:text-gray-300
                        prose-li:text-gray-300
                        prose-blockquote:border-l-[#d4af37] prose-blockquote:text-gray-400 prose-blockquote:italic
                        prose-code:text-[#d4af37] prose-code:bg-[#1e293b] prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded
                        prose-pre:bg-[#0b1221] prose-pre:border prose-pre:border-[#1e293b]
                        prose-hr:border-[#1e293b]
                        prose-img:rounded-xl prose-img:shadow-lg">
                {!! $news->content !!}
            </div>

            {{-- Attachments Section --}}
            @if($news->attachment)
            <div class="mt-10 pt-8 border-t border-[#1e293b]">
                <h3 class="flex items-center gap-2 text-lg font-bold text-white mb-4">
                    <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    Lampiran
                </h3>
                
                <div class="bg-[#0b1221] border border-[#1e293b] rounded-xl p-4 hover:border-[#d4af37]/50 transition-colors group">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            {{-- File Icon --}}
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#d4af37]/20 to-[#d4af37]/5 border border-[#d4af37]/30 flex items-center justify-center flex-shrink-0">
                                @php
                                    $extension = pathinfo($news->attachment, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array(strtolower($extension), ['pdf']))
                                    <svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4zM8.5 13h1c.83 0 1.5.67 1.5 1.5S10.33 16 9.5 16H9v1.5H8.5V13zm1 2h-.5v-1h.5c.28 0 .5.22.5.5s-.22.5-.5.5zm2.5-2h1.5c.83 0 1.5.67 1.5 1.5v2c0 .83-.67 1.5-1.5 1.5H12V13zm1.5 4.5c.28 0 .5-.22.5-.5v-2c0-.28-.22-.5-.5-.5h-.5v3h.5zM15 13h2v.5h-1.5v1h1v.5h-1v2H15v-4z"/>
                                    </svg>
                                @elseif(in_array(strtolower($extension), ['doc', 'docx']))
                                    <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4zM8.5 13L9.5 18l1-3.5h1l1 3.5 1-5h1l-1.5 5.5h-1L11 15l-1 3.5h-1L7.5 13h1z"/>
                                    </svg>
                                @elseif(in_array(strtolower($extension), ['xls', 'xlsx']))
                                    <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4zm-2 9l2 3-2 3h1.5l1.25-2 1.25 2H16l-2-3 2-3h-1.5l-1.25 2-1.25-2H10.5z"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                @endif
                            </div>
                            
                            {{-- File Info --}}
                            <div class="min-w-0">
                                <p class="text-white font-medium truncate">{{ basename($news->attachment) }}</p>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">{{ strtoupper($extension) }} File</p>
                            </div>
                        </div>
                        
                        {{-- Download Button --}}
                        <a href="{{ asset('storage/' . $news->attachment) }}" 
                           download 
                           class="flex-shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#d4af37] to-[#b8962e] text-[#0b1221] font-bold text-sm hover:from-[#e5c04a] hover:to-[#c9a341] transform hover:scale-105 transition-all duration-200 shadow-lg shadow-[#d4af37]/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span class="hidden sm:inline">Download</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Multiple Attachments (if using array) --}}
            @if(isset($news->attachments) && is_array($news->attachments) && count($news->attachments) > 0)
            <div class="mt-10 pt-8 border-t border-[#1e293b]">
                <h3 class="flex items-center gap-2 text-lg font-bold text-white mb-4">
                    <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    Lampiran ({{ count($news->attachments) }} File)
                </h3>
                
                <div class="grid gap-3">
                    @foreach($news->attachments as $attachment)
                    <div class="bg-[#0b1221] border border-[#1e293b] rounded-xl p-4 hover:border-[#d4af37]/50 transition-colors">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-[#1e293b] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-300 font-medium truncate">{{ basename($attachment) }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $attachment) }}" 
                               download 
                               class="flex-shrink-0 p-2 rounded-lg bg-[#1e293b] text-[#d4af37] hover:bg-[#d4af37] hover:text-[#0b1221] transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Footer Navigation --}}
        <div class="px-6 md:px-10 lg:px-12 py-6 bg-[#0b1221]/50 border-t border-[#1e293b]">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                {{-- Back Button --}}
                <a href="{{ route('admin.news.index') }}" 
                    class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Berita
                </a>
                
                {{-- Admin Actions --}}
                @if(auth()->user()->role === 'admin')
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.news.edit', $news->id) }}" 
                       class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                        EDIT
                    </a>
                    
                    <button type="button"
                            @click="$dispatch('confirm-delete', { 
                                url: '{{ route('admin.news.destroy', $news->id) }}',
                                title: 'Hapus Berita',
                                message: 'Apakah Anda yakin ingin menghapus berita \'{{ $news->title }}\'? Tindakan ini tidak dapat dibatalkan.'
                            })"
                            class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-red-500/50 text-red-400 hover:bg-red-500/10 hover:border-red-500 transition-all duration-200 text-sm font-bold">
                        HAPUS
                    </button>
                </div>
                @endif
            </div>
        </div>
    </article>

    {{-- Share Section (Optional Enhancement) --}}
    <div class="mt-8 text-center">
        <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Bagikan Berita</p>
        <div class="flex justify-center gap-3">
            <a href="https://wa.me/?text={{ urlencode($news->title . ' - ' . url()->current()) }}" 
               target="_blank"
               class="w-10 h-10 rounded-full bg-[#1e293b] border border-[#2d3a4f] flex items-center justify-center text-gray-400 hover:bg-green-600 hover:border-green-600 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($news->title) }}&url={{ urlencode(url()->current()) }}" 
               target="_blank"
               class="w-10 h-10 rounded-full bg-[#1e293b] border border-[#2d3a4f] flex items-center justify-center text-gray-400 hover:bg-sky-500 hover:border-sky-500 hover:text-white transition-all duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </a>
            <button onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Link berhasil disalin!')"
                    class="w-10 h-10 rounded-full bg-[#1e293b] border border-[#2d3a4f] flex items-center justify-center text-gray-400 hover:bg-[#d4af37] hover:border-[#d4af37] hover:text-[#0b1221] transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </button>
        </div>
    </div>

</div>
@endsection