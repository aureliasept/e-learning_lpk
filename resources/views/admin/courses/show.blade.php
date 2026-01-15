<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Kelola: {{ $course->title }}
            </h2>
            <a href="{{ route('admin.courses.index') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 shadow-sm rounded-lg border-t-4 border-t-blue-500">
                    <h3 class="font-bold text-lg mb-4 pb-2 border-b">📂 Upload Materi Baru</h3>
                    <form action="{{ route('admin.materials.store', $course) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-700">Judul Materi</label>
                            <input type="text" name="title" class="w-full border-gray-300 rounded focus:ring-blue-500" required placeholder="Contoh: Modul Bab 1">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-700">Tipe Konten</label>
                            <select name="type" class="w-full border-gray-300 rounded focus:ring-blue-500">
                                <option value="pdf">Dokumen (PDF/Word)</option>
                                <option value="video">Video (Upload File)</option>
                                <option value="link">Link Eksternal (Youtube/Drive)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-700">File (Opsional)</label>
                            <input type="file" name="file" class="w-full border border-gray-300 p-1 rounded bg-gray-50">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-700">Link URL (Opsional)</label>
                            <input type="url" name="link" class="w-full border-gray-300 rounded" placeholder="https://...">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold">Simpan Materi</button>
                    </form>
                </div>

                <div class="bg-white p-6 shadow-sm rounded-lg border-t-4 border-t-red-500">
                    <h3 class="font-bold text-lg mb-4 pb-2 border-b">📝 Buat Tugas Baru</h3>
                    <form action="{{ route('admin.assignments.store', $course) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-700">Judul Tugas</label>
                            <input type="text" name="title" class="w-full border-gray-300 rounded focus:ring-red-500" required placeholder="Contoh: Tugas Harian 1">
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-700">Instruksi Soal</label>
                            <textarea name="description" rows="3" class="w-full border-gray-300 rounded focus:ring-red-500"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-700">Deadline</label>
                            <input type="datetime-local" name="deadline" class="w-full border-gray-300 rounded" required>
                        </div>
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-bold">Buat Tugas</button>
                    </form>
                </div>
            </div>

            <div class="bg-white p-6 shadow-sm rounded-lg">
                <h3 class="font-bold text-xl mb-4 text-gray-800">📚 Isi Kelas Saat Ini</h3>
                
                @if($materials->count() == 0 && $assignments->count() == 0)
                    <div class="text-center py-8 bg-gray-50 rounded border border-dashed border-gray-300">
                        <p class="text-gray-500">Belum ada materi atau tugas. Silakan tambahkan melalui form di atas.</p>
                    </div>
                @else
                    <ul class="space-y-3">
                        @foreach($materials as $m)
                            <li class="flex items-center justify-between bg-blue-50 p-4 rounded border border-blue-100 hover:shadow-md transition">
                                <div class="flex items-center gap-3">
                                    <span class="bg-blue-200 text-blue-800 text-xs px-2 py-1 rounded font-bold uppercase">{{ $m->type }}</span>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $m->title }}</p>
                                        <p class="text-xs text-gray-500">Diupdate: {{ $m->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    @if($m->type == 'link') 🔗 Link @else 📎 File @endif
                                </div>
                            </li>
                        @endforeach

                        @foreach($assignments as $a)
                            <li class="flex items-center justify-between bg-red-50 p-4 rounded border border-red-100 hover:shadow-md transition">
                                <div class="flex items-center gap-3">
                                    <span class="bg-red-200 text-red-800 text-xs px-2 py-1 rounded font-bold uppercase">TUGAS</span>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $a->title }}</p>
                                        <p class="text-xs text-red-600">Deadline: {{ \Carbon\Carbon::parse($a->deadline)->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    ⏳ Pending
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>