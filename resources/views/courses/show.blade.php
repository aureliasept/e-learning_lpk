<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pelatihan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="border-b pb-4 mb-4">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $course->title }}</h1>
                    <div class="flex items-center gap-4 mt-2">
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-semibold px-3 py-1 rounded">
                            {{ $course->code ?? 'KODE-001' }}
                        </span>
                        <span class="text-gray-500 text-sm">
                            Dibuat: {{ $course->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Tentang Pelatihan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $course->description ?? 'Tidak ada deskripsi untuk pelatihan ini.' }}
                    </p>
                </div>

                @auth
                    <div class="bg-gray-50 rounded-lg p-6 border">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">📚 Materi Pembelajaran</h3>
                        
                        @if($course->materials->count() > 0)
                            <ul class="space-y-3">
                                @foreach($course->materials as $material)
                                <li class="flex items-center justify-between bg-white p-3 rounded shadow-sm border">
                                    <div class="flex items-center gap-3">
                                        <span class="bg-blue-100 text-blue-600 p-2 rounded">
                                            @if($material->type == 'pdf') 📄
                                            @elseif($material->type == 'video') 🎬
                                            @else 🔗
                                            @endif
                                        </span>
                                        <div>
                                            <p class="font-bold text-gray-800">{{ $material->title }}</p>
                                            <p class="text-xs text-gray-500 capitalize">{{ $material->type }}</p>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        @if($material->type == 'link')
                                            <a href="{{ $material->content }}" target="_blank" class="text-blue-600 hover:underline text-sm font-bold">Buka Link &rarr;</a>
                                        @elseif($material->file_path)
                                            <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm font-bold">Download &rarr;</a>
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 italic text-center py-4">Belum ada materi yang diunggah instruktur.</p>
                        @endif
                    </div>
                @else
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Materi terkunci. Silakan 
                                    <a href="{{ route('login') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">Masuk</a> 
                                    atau 
                                    <a href="{{ route('register') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">Daftar</a>
                                    untuk mengakses materi.
                                </p>
                            </div>
                        </div>
                    </div>
                @endauth

            </div>
        </div>
    </div>
</x-app-layout>