@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12" style="background-color: #0f172a; color: white;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="rounded-xl border border-gray-700 shadow-xl p-8 mb-8" style="background-color: #0f172a;">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="text-xs font-bold uppercase tracking-widest text-gray-400">Belajar</div>
                    <h1 class="text-3xl font-bold uppercase tracking-tight text-white mt-2">
                        {{ $course->name ?? $course->nama ?? $course->title ?? '-' }}
                    </h1>
                    <p class="text-gray-400 text-sm mt-2">
                        {{ $course->description ?? $course->deskripsi ?? 'Tidak ada deskripsi.' }}
                    </p>
                </div>

                <a href="{{ route('student.courses.index') }}"
                   class="px-4 py-2 rounded text-xs font-bold uppercase tracking-widest shadow-md no-underline"
                   style="background-color: #1e293b; border: 1px solid #c9a341; color: #c9a341;">
                    Kembali
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-gray-700 shadow-xl p-4 mb-6" style="background-color: #1e293b;">
                <div class="text-sm text-white">
                    <span class="font-bold" style="color: #c9a341;">Sukses:</span>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="rounded-xl border border-gray-700 shadow-xl" style="background-color: #1e293b;">
            <div class="p-6 border-b border-white/10">
                <h2 class="text-xl font-bold uppercase tracking-widest text-white border-l-4 pl-4" style="border-color: #c9a341;">
                    Modul Pembelajaran
                </h2>
                <div class="text-sm text-gray-400 mt-2">Klik judul modul untuk membuka/tutup.</div>
            </div>

            <div x-data="{ open: null }" class="divide-y divide-white/10">
                @forelse($modules as $index => $module)
                    @php
                        $moduleTitle = $module->title ?? $module->judul ?? $module->name ?? $module->nama ?? null;
                        if (! $moduleTitle) {
                            $moduleTitle = 'Bab ' . ($index + 1);
                        }

                        $moduleDescription = $module->description ?? $module->deskripsi ?? null;

                        $materials = $module->materials ?? null;
                        if ($materials === null && method_exists($module, 'assignments')) {
                            $materials = collect([$module]);
                        }

                        $assignments = $module->assignments ?? collect();
                    @endphp

                    <div class="p-6">
                        <button type="button"
                                @click="open === {{ $index }} ? open = null : open = {{ $index }}"
                                class="w-full flex items-center justify-between text-left">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-widest" style="color: #c9a341;">Modul {{ $index + 1 }}</div>
                                <div class="text-lg font-extrabold text-white mt-1">{{ $moduleTitle }}</div>
                                @if($moduleDescription)
                                    <div class="text-sm text-gray-400 mt-2">{{ $moduleDescription }}</div>
                                @endif
                            </div>

                            <div class="text-xs font-bold uppercase tracking-widest px-3 py-2 rounded"
                                 style="background-color: rgba(15,23,42,0.7); border: 1px solid rgba(201,163,65,0.35); color: #c9a341;">
                                <span x-show="open !== {{ $index }}" x-cloak>Buka</span>
                                <span x-show="open === {{ $index }}" x-cloak>Tutup</span>
                            </div>
                        </button>

                        <div x-show="open === {{ $index }}" x-cloak class="mt-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                                <div class="rounded-xl p-5" style="background-color: rgba(15,23,42,0.6); border: 1px solid rgba(201,163,65,0.25);">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="text-sm font-extrabold uppercase tracking-widest" style="color: #c9a341;">Materi</div>
                                    </div>

                                    <div class="mt-4 space-y-3">
                                        @php
                                            $materialItems = collect();

                                            if ($module->materials ?? false) {
                                                $materialItems = collect($module->materials);
                                            } elseif ($materials instanceof \Illuminate\Support\Collection) {
                                                $materialItems = $materials;
                                            } elseif ($materials) {
                                                $materialItems = collect($materials);
                                            }
                                        @endphp

                                        @if($materialItems->count() === 0)
                                            <div class="text-sm text-gray-400">Belum ada materi.</div>
                                        @else
                                            @foreach($materialItems as $material)
                                                @php
                                                    $title = $material->title ?? $material->judul ?? $material->name ?? $material->nama ?? '-';
                                                    $file = $material->file_path ?? null;
                                                    $url = $file ? asset('storage/' . ltrim($file, '/')) : null;
                                                    $comments = $material->comments ?? collect();
                                                @endphp

                                                <div class="p-4 rounded-lg border border-white/10" style="background-color: #1e293b;">
                                                    <div class="flex items-start justify-between gap-4">
                                                        <div>
                                                            <div class="font-bold text-white">{{ $title }}</div>
                                                        </div>

                                                        @if($url)
                                                            <a href="{{ $url }}" target="_blank"
                                                               class="px-3 py-2 rounded text-[11px] font-bold uppercase tracking-widest no-underline"
                                                               style="background-color: #0f172a; border: 1px solid #c9a341; color: #c9a341;">
                                                                Lihat / Download
                                                            </a>
                                                        @endif
                                                    </div>

                                                    <div class="mt-4 rounded-lg p-4" style="background-color: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.08);">
                                                        <div class="text-xs font-bold uppercase tracking-widest" style="color:#c9a341;">Diskusi Materi</div>

                                                        <div class="mt-3 space-y-3">
                                                            @if(($comments ?? collect())->count() === 0)
                                                                <div class="text-sm text-gray-400">Belum ada komentar.</div>
                                                            @else
                                                                @foreach(($comments ?? collect()) as $comment)
                                                                    <div class="rounded-xl px-4 py-3 border border-white/10" style="background-color: #0f172a;">
                                                                        <div class="text-xs font-bold uppercase tracking-widest" style="color:#c9a341;">
                                                                            {{ $comment->user->name ?? 'User' }}
                                                                        </div>
                                                                        <div class="text-sm text-white mt-1 whitespace-pre-line">{{ $comment->body }}</div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>

                                                        <form method="POST" action="{{ route('student.comments.store') }}" class="mt-4 space-y-3">
                                                            @csrf
                                                            <input type="hidden" name="commentable_type" value="material" />
                                                            <input type="hidden" name="commentable_id" value="{{ $material->id }}" />
                                                            <textarea name="body" rows="2" required
                                                                      placeholder="Tulis komentar..."
                                                                      class="w-full rounded bg-[#0f172a] text-white border-gray-700 focus:border-[#c9a341]"></textarea>
                                                            <div class="flex justify-end">
                                                                <button type="submit"
                                                                        class="px-4 py-2 rounded bg-[#c9a341] text-[#0f172a] font-bold uppercase tracking-widest">
                                                                    Kirim
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="rounded-xl p-5" style="background-color: rgba(15,23,42,0.6); border: 1px solid rgba(201,163,65,0.25);">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="text-sm font-extrabold uppercase tracking-widest" style="color: #c9a341;">Tugas</div>
                                    </div>

                                    <div class="mt-4 space-y-3">
                                        @php
                                            $assignmentItems = collect();

                                            if ($module->assignments ?? false) {
                                                $assignmentItems = collect($module->assignments);
                                            } elseif ($assignments) {
                                                $assignmentItems = collect($assignments);
                                            }
                                        @endphp

                                        @if($assignmentItems->count() === 0)
                                            <div class="text-sm text-gray-400">Belum ada tugas.</div>
                                        @else
                                            @foreach($assignmentItems as $assignment)
                                                @php
                                                    $title = $assignment->title ?? $assignment->judul ?? '-';
                                                    $deadline = $assignment->deadline ?? null;
                                                    $file = $assignment->file_path ?? null;
                                                    $url = $file ? asset('storage/' . ltrim($file, '/')) : null;
                                                @endphp

                                                <div class="p-4 rounded-lg border border-white/10" style="background-color: #1e293b;">
                                                    <div class="flex items-start justify-between gap-4">
                                                        <div>
                                                            <div class="font-bold text-white">{{ $title }}</div>
                                                            @if($deadline)
                                                                <div class="text-xs text-gray-400 mt-2">Deadline: {{ \Illuminate\Support\Carbon::parse($deadline)->format('d M Y H:i') }}</div>
                                                            @endif
                                                        </div>

                                                        <div class="flex items-center gap-2">
                                                            @if($url)
                                                                <a href="{{ $url }}" target="_blank"
                                                                   class="px-3 py-2 rounded text-[11px] font-bold uppercase tracking-widest no-underline"
                                                                   style="background-color: #0f172a; border: 1px solid rgba(255,255,255,0.12); color: white;">
                                                                    Download
                                                                </a>
                                                            @endif

                                                            <button type="button" disabled
                                                                    class="px-3 py-2 rounded text-[11px] font-bold uppercase tracking-widest opacity-60 cursor-not-allowed"
                                                                    style="background-color: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.35); color: #bfdbfe;">
                                                                Upload Tugas
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6">
                        <div class="text-sm text-gray-400">Belum ada modul untuk course ini.</div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
