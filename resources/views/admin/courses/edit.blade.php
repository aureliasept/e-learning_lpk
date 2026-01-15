@extends('layouts.app')

@section('content')
<div class="py-12 bg-lpk-navy min-h-screen" x-data="{ 
    editMaterialModalOpen: false, 
    addAssignmentModalOpen: false,
    selectedMaterial: null, 
    openEditMaterialModal(material) { this.selectedMaterial = material; this.editMaterialModalOpen = true; },
    openAddAssignmentModal(materialId) { this.selectedMaterial = { id: materialId }; this.addAssignmentModalOpen = true; }
}">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 relative">
        <div class="mb-6 px-4 sm:px-0">
            <a href="{{ route('admin.courses.index') }}" class="text-gray-400 hover:text-lpk-gold text-sm font-medium mb-2 inline-block transition hover:-translate-x-1">&larr; Kembali ke Daftar Modul</a>
            <h2 class="text-3xl font-bold text-white mt-2">Kelola Modul: <span class="text-lpk-gold">{{ $course->title }}</span></h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- KOLOM KIRI: INFO MODUL --}}
            <div class="lg:col-span-1">
                <div class="bg-lpk-navy-light p-6 rounded-xl border border-lpk-gold/20 shadow-lg sticky top-6">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-gray-700 pb-2">Info Modul</h3>
                    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" class="space-y-5">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-lpk-gold uppercase mb-1">Nama Modul</label>
                            <input type="text" name="title" value="{{ $course->title }}" class="w-full bg-lpk-navy border border-gray-600 rounded-lg text-white text-sm px-4 py-3 outline-none transition focus:border-lpk-gold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-lpk-gold uppercase mb-1">Level</label>
                            <select name="level" class="w-full bg-lpk-navy border border-gray-600 rounded-lg text-white text-sm px-4 py-3 outline-none transition focus:border-lpk-gold">
                                <option value="pemula" {{ $course->level == 'pemula' ? 'selected' : '' }}>Pemula</option>
                                <option value="menengah" {{ $course->level == 'menengah' ? 'selected' : '' }}>Menengah</option>
                                <option value="mahir" {{ $course->level == 'mahir' ? 'selected' : '' }}>Mahir</option>
                            </select>
                        </div>
                        <button type="submit" class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">Simpan Perubahan</button>
                    </form>
                </div>
            </div>

            {{-- KOLOM KANAN: DAFTAR BAB --}}
            <div class="lg:col-span-2 space-y-6">
                @if(session('success')) <div class="bg-green-900/50 border border-green-500 text-green-300 px-4 py-3 rounded-lg">{{ session('success') }}</div> @endif
                @if ($errors->any()) <div class="bg-red-900/50 border border-red-500 text-red-300 px-4 py-3 rounded-lg"><ul>@foreach ($errors->all() as $error) <li>- {{ $error }}</li> @endforeach</ul></div> @endif

                {{-- FORM TAMBAH BAB CEPAT --}}
                <div class="bg-lpk-navy border border-lpk-gold/30 p-4 rounded-xl shadow-lg flex gap-3 items-center">
                    <h3 class="text-sm font-bold text-white whitespace-nowrap">Bab Baru:</h3>
                    <form action="{{ route('admin.courses.materials.store', $course->id) }}" method="POST" class="flex-grow flex gap-2"> 
                        @csrf
                        <input type="text" name="title" placeholder="Judul Bab..." class="flex-grow bg-lpk-navy-light border border-gray-600 rounded-lg text-white px-3 py-2 text-sm focus:ring-1 focus:ring-lpk-gold outline-none" required>
                        <button type="submit" class="bg-lpk-gold text-lpk-navy font-bold px-4 py-2 rounded-lg hover:bg-white transition text-sm">+ Tambah</button>
                    </form>
                </div>

                {{-- LIST BAB --}}
                <div class="bg-lpk-navy-light rounded-xl border border-gray-700 overflow-hidden shadow-lg">
                    <div class="bg-lpk-navy px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                        <h3 class="font-bold text-white">Daftar Materi & Tugas</h3>
                        <span class="text-xs bg-gray-800 text-lpk-gold border border-lpk-gold/30 px-3 py-1 rounded-full font-mono">Total: {{ $course->materials->count() }} Bab</span>
                    </div>
                    <div class="divide-y divide-gray-700 max-h-[700px] overflow-y-auto">
                        @forelse($course->materials as $index => $material)
                        <div class="p-4 hover:bg-lpk-navy transition group flex flex-col gap-3">
                            {{-- HEADER BAB --}}
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-3">
                                    <span class="bg-gray-800 text-gray-400 w-7 h-7 flex-shrink-0 flex items-center justify-center rounded-full text-xs font-bold border border-gray-700">{{ $index + 1 }}</span>
                                    <div>
                                        <h4 class="text-gray-200 font-bold text-base">{{ $material->title }}</h4>
                                        @if($material->description) <p class="text-gray-400 text-xs line-clamp-1">{{ $material->description }}</p> @endif
                                    </div>
                                </div>
                                <div class="flex gap-2 opacity-70 group-hover:opacity-100 transition">
                                    <button @click="openEditMaterialModal({{ $material }})" class="text-xs text-lpk-gold border border-lpk-gold px-2 py-1 rounded hover:bg-lpk-gold hover:text-lpk-navy flex items-center gap-1">Edit / Upload</button>
                                    <button @click="openAddAssignmentModal({{ $material->id }})" class="text-xs text-blue-400 border border-blue-400 px-2 py-1 rounded hover:bg-blue-400 hover:text-white flex items-center gap-1">+ Tugas</button>
                                    <form action="{{ route('admin.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Hapus bab ini?');">@csrf @method('DELETE')<button class="text-xs text-red-400 border border-red-400 px-2 py-1 rounded hover:bg-red-500 hover:text-white">Hapus</button></form>
                                </div>
                            </div>
                            {{-- CONTENT BAB --}}
                            <div class="ml-10 space-y-2">
                                @if($material->file_path)
                                    <div class="flex items-center gap-2 text-sm text-lpk-gold bg-lpk-navy/50 p-2 rounded border border-lpk-gold/10">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="hover:underline">Lihat File Materi</a>
                                    </div>
                                @endif
                                @foreach($material->assignments as $assignment)
                                <div class="flex justify-between items-center bg-gray-800/50 p-2 rounded border-l-2 border-blue-500 ml-2 text-sm">
                                    <div><span class="font-bold text-blue-300">Tugas:</span> <span class="text-gray-300">{{ $assignment->title }}</span></div>
                                    <div class="flex gap-2">
                                        @if($assignment->file_path) <a href="{{ Storage::url($assignment->file_path) }}" target="_blank" class="text-xs text-blue-400 hover:underline">Lihat Soal</a> @endif
                                        <form action="{{ route('admin.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?');">@csrf @method('DELETE')<button class="text-xs text-red-400 hover:text-red-300">Hapus</button></form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @empty
                        <div class="p-8 text-center text-gray-500 italic">Belum ada bab materi.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- MODALS POPUP --}}
        <div x-show="editMaterialModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                <div class="bg-lpk-navy-light rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full border border-lpk-gold/20 relative z-10">
                    <div class="bg-lpk-navy px-4 py-3 border-b border-lpk-gold/10 flex justify-between items-center"><h3 class="font-bold text-white">Edit Bab</h3><button @click="editMaterialModalOpen = false" class="text-gray-400 hover:text-white">&times;</button></div>
                    <div class="p-6">
                        <form :action="`/admin/materials/${selectedMaterial.id}`" method="POST" enctype="multipart/form-data" id="editMaterialForm" class="space-y-4">
                            @csrf @method('PUT')
                            <div><label class="block text-sm font-bold text-lpk-gold mb-1">Judul Bab</label><input type="text" name="title" :value="selectedMaterial.title" class="w-full bg-lpk-navy border border-gray-600 rounded-lg text-white px-3 py-2 outline-none" required></div>
                            <div><label class="block text-sm font-bold text-lpk-gold mb-1">Upload File (PDF/Video)</label><input type="file" name="file_material" class="w-full text-sm text-gray-300" accept=".pdf,.mp4,.doc,.docx"></div>
                        </form>
                    </div>
                    <div class="bg-lpk-navy px-4 py-3 sm:flex sm:flex-row-reverse border-t border-lpk-gold/10"><button type="submit" form="editMaterialForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-lpk-gold text-lpk-navy font-bold hover:bg-white sm:ml-3 sm:w-auto sm:text-sm">Simpan</button><button @click="editMaterialModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 px-4 py-2 bg-lpk-navy text-gray-300 hover:bg-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button></div>
                </div>
            </div>
        </div>

        <div x-show="addAssignmentModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75"></div>
                <div class="bg-lpk-navy-light rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full border border-blue-500/30 relative z-10">
                    <div class="bg-gray-900 px-4 py-3 border-b border-blue-500/20 flex justify-between items-center"><h3 class="font-bold text-blue-300">Tambah Tugas</h3><button @click="addAssignmentModalOpen = false" class="text-gray-400 hover:text-white">&times;</button></div>
                    <div class="p-6">
                        <form :action="`/admin/materials/${selectedMaterial.id}/assignments`" method="POST" enctype="multipart/form-data" id="addAssignmentForm" class="space-y-4">
                            @csrf
                            <div><label class="block text-sm font-bold text-blue-300 mb-1">Judul Tugas</label><input type="text" name="title" class="w-full bg-lpk-navy border border-gray-600 rounded-lg text-white px-3 py-2 outline-none" required></div>
                            <div><label class="block text-sm font-bold text-blue-300 mb-1">Upload Soal (Opsional)</label><input type="file" name="file_assignment" class="w-full text-sm text-gray-300" accept=".pdf,.doc,.docx"></div>
                        </form>
                    </div>
                    <div class="bg-gray-900 px-4 py-3 sm:flex sm:flex-row-reverse border-t border-blue-500/20"><button type="submit" form="addAssignmentForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-white font-bold hover:bg-blue-600 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button><button @click="addAssignmentModalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 px-4 py-2 bg-lpk-navy text-gray-300 hover:bg-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button></div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection