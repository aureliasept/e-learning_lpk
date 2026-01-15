@extends('layouts.app')

@section('content')
<div class="py-12 bg-lpk-navy min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6 flex justify-between items-center px-4 sm:px-0">
            <h2 class="text-2xl font-bold text-white">Unggah Modul Pembelajaran</h2>
            <a href="{{ route('admin.courses.index') }}" class="text-gray-400 hover:text-lpk-gold transition text-sm font-medium flex items-center gap-1">
                &larr; Kembali
            </a>
        </div>

        <div class="bg-lpk-navy-light rounded-2xl shadow-xl border border-lpk-gold/20 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-lpk-gold mb-2">Judul Modul</label>
                        <input type="text" name="title" class="w-full bg-lpk-navy border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-lpk-gold focus:ring-1 focus:ring-lpk-gold focus:outline-none transition" placeholder="Contoh: MODUL 1" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-lpk-gold mb-2">Deskripsi Singkat</label>
                        <textarea name="description" rows="4" class="w-full bg-lpk-navy border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-lpk-gold focus:ring-1 focus:ring-lpk-gold focus:outline-none transition" placeholder="Jelaskan isi modul ini..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-lpk-gold mb-2">Kategori / Level</label>
                        <select name="level" class="w-full bg-lpk-navy border border-gray-600 rounded-lg px-4 py-3 text-white focus:border-lpk-gold focus:ring-1 focus:ring-lpk-gold focus:outline-none transition">
                            <option value="pemula">Pemula / Dasar</option>
                            <option value="menengah">Menengah</option>
                            <option value="mahir">Lanjutan / Mahir</option>
                        </select>
                    </div>

                    <div>
                         <label class="block text-sm font-bold text-lpk-gold mb-2">Cover Modul (Opsional)</label>
                         <div class="flex items-center justify-center w-full">
                            <label for="cover_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-600 border-dashed rounded-lg cursor-pointer bg-lpk-navy hover:bg-gray-800 transition group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-lpk-gold transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <p class="text-sm text-gray-400 group-hover:text-white">Klik untuk upload cover</p>
                                </div>
                                <input id="cover_image" name="cover_image" type="file" class="hidden" />
                            </label>
                        </div> 
                    </div>

                    <div class="pt-4 border-t border-gray-700">
                        <button type="submit" class="w-full flex justify-center items-center gap-2 bg-lpk-gold text-lpk-navy font-bold py-3 px-4 rounded-lg hover:bg-white hover:text-lpk-navy transition duration-300 transform hover:-translate-y-1 shadow-lg uppercase tracking-wide">
                            Simpan Modul
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection