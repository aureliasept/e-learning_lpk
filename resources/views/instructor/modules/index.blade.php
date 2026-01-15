@extends('instructor.layouts.app')

@section('title', 'Gudang Materi')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
        <span class="text-[#d4af37] font-medium">Gudang Materi</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-7 h-7 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Gudang Materi</h1>
                <p class="text-sm text-gray-400">Kelola materi pembelajaran untuk siswa</p>
            </div>
        </div>
        <a href="{{ route('instructor.modules.create', ['year' => $selectedYearId, 'batch' => $selectedBatchId]) }}" 
            class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            TAMBAH MATERI
        </a>
    </div>

    {{-- Access Info --}}
    <div class="bg-[#1e293b]/50 border border-[#1e293b] rounded-xl p-4 mb-6 flex items-center gap-3">
        <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-sm text-gray-300">
            Akses Anda:
            @if($canTeachReguler && $canTeachKaryawan)
                <span class="text-[#d4af37] font-bold">Reguler & Karyawan</span>
            @elseif($canTeachReguler)
                <span class="text-[#d4af37] font-bold">Reguler</span>
            @elseif($canTeachKaryawan)
                <span class="text-[#d4af37] font-bold">Karyawan</span>
            @else
                <span class="text-red-400 font-bold">Tidak ada akses kelas</span>
            @endif
        </span>
    </div>

    @if(session('success'))
        <div class="bg-green-900/30 border border-green-500/50 text-green-300 p-4 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-6 mb-6">
        <form method="GET" action="{{ route('instructor.modules.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4" x-data="{ 
            years: {{ $trainingYears->toJson() }},
            selectedYear: {{ $selectedYearId ?? 'null' }},
            batches: {{ $batches->toJson() }}
        }">
            {{-- Year Filter --}}
            <div>
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Tahun Periode</label>
                <select name="year" @change="
                    let yr = years.find(y => y.id == $event.target.value);
                    batches = yr ? yr.batches : [];
                    $refs.batchSelect.value = batches.length > 0 ? batches[0].id : '';
                    $el.form.submit();
                " class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37]">
                    @foreach($trainingYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Batch Filter --}}
            <div>
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Gelombang</label>
                <select name="batch" x-ref="batchSelect" @change="$el.form.submit()"
                    class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37]">
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $selectedBatchId == $batch->id ? 'selected' : '' }}>
                            {{ $batch->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Class Type Filter --}}
            <div>
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2">Tipe Kelas</label>
                <select name="class_type" @change="$el.form.submit()"
                    class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37]">
                    <option value="all" {{ $selectedClassType == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="reguler" {{ $selectedClassType == 'reguler' ? 'selected' : '' }}>Reguler</option>
                    <option value="karyawan" {{ $selectedClassType == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                    <option value="both" {{ $selectedClassType == 'both' ? 'selected' : '' }}>Keduanya</option>
                </select>
            </div>

            {{-- Stats --}}
            <div class="flex items-end">
                <div class="w-full bg-[#1e293b]/50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-[#d4af37]">{{ $modules->total() }}</p>
                    <p class="text-xs text-gray-400">Total Materi</p>
                </div>
            </div>
        </form>
    </div>

    {{-- Modules List --}}
    <div class="space-y-4">
        @forelse($modules as $module)
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            {{-- Icon --}}
                            <div class="h-10 w-10 rounded-xl bg-[#1e293b] flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-[#1e293b] text-gray-400">MATERI</span>
                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $module->class_type == 'reguler' ? 'bg-blue-500/10 text-blue-400' : ($module->class_type == 'karyawan' ? 'bg-purple-500/10 text-purple-400' : 'bg-green-500/10 text-green-400') }}">
                                        {{ strtoupper($module->class_type ?? 'BOTH') }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $module->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <h3 class="text-lg font-bold text-white mb-2">{{ $module->title }}</h3>
                                
                                @if($module->description)
                                    <p class="text-sm text-gray-400 line-clamp-2">{{ Str::limit($module->description, 150) }}</p>
                                @endif

                                <div class="flex flex-wrap items-center gap-3 mt-3">
                                    @if($module->file_path)
                                        <a href="{{ asset('storage/' . $module->file_path) }}" target="_blank" 
                                            class="inline-flex items-center text-sm text-[#d4af37] hover:underline">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download {{ strtoupper($module->file_type ?? 'FILE') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('instructor.modules.edit', $module) }}" 
                                class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                                EDIT
                            </a>
                            <button type="button"
                                x-data
                                @click="$dispatch('confirm-delete', { 
                                    url: '{{ route('instructor.modules.destroy', $module) }}',
                                    title: 'Hapus Materi',
                                    message: 'Apakah Anda yakin ingin menghapus materi \'{{ $module->title }}\'? Tindakan ini tidak dapat dibatalkan.'
                                })"
                                class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-red-500 text-red-400 hover:text-white hover:bg-red-500 hover:border-red-500 transition-all duration-200 text-sm font-bold">
                                HAPUS
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl p-12 text-center">
                <div class="h-16 w-16 rounded-full bg-[#1e293b] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Belum Ada Materi</h3>
                <p class="text-gray-400 mb-6">Buat materi pertama untuk filter ini.</p>
                <a href="{{ route('instructor.modules.create', ['year' => $selectedYearId, 'batch' => $selectedBatchId]) }}" 
                class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    TAMBAH MATERI
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $modules->links() }}
    </div>
</div>
@endsection