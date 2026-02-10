@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm mb-6">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-[#d4af37] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('admin.classes.' . ($type ?? 'reguler')) }}" class="text-gray-400 hover:text-[#d4af37] transition">Kelas {{ ucfirst($type ?? 'Reguler') }}</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">Tambah Siswa</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-6 h-6 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Tambah Siswa Baru</h1>
                <p class="text-sm text-gray-400">Lengkapi biodata siswa untuk kelas {{ ucfirst($type ?? 'reguler') }}</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        <form action="{{ route('admin.students.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="mx-8 mt-8 bg-red-900/30 border border-red-500/50 text-red-300 p-4 rounded-xl">
                    <p class="font-semibold text-sm mb-1">Terjadi kesalahan:</p>
                    <ul class="text-sm space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            {{-- Section: Informasi Akun --}}
            <div class="bg-[#0b1221]/50 border-b border-[#1e293b] px-8 py-4 mt-0">
                <h2 class="text-sm font-bold text-[#d4af37] uppercase tracking-wider">Informasi Akun</h2>
            </div>

            <div class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama sesuai ijazah"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all">
                    </div>

                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            NIK (Nomor Induk Kependudukan) <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nik" value="{{ old('nik') }}" required placeholder="16 digit angka NIK"
                            maxlength="16" pattern="\d{16}" inputmode="numeric"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all font-mono tracking-wider">
                        <p class="mt-1 text-xs text-gray-500">NIK digunakan untuk login siswa</p>
                        @error('nik')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Email (Opsional)
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@siswa.com"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all">
                    </div>

                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Password <span class="text-red-400">*</span>
                        </label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all">
                    </div>
                </div>
            </div>

            {{-- Section: Biodata & Akademik --}}
            <div class="bg-[#0b1221]/50 border-y border-[#1e293b] px-8 py-4">
                <h2 class="text-sm font-bold text-[#d4af37] uppercase tracking-wider">Biodata & Akademik</h2>
            </div>

            <div class="p-8 space-y-6">
                {{-- Tahun Pelatihan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Tahun Pelatihan (Angkatan)
                        </label>
                        <select name="training_year_id"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all appearance-none">
                            <option value="">Pilih Tahun</option>
                            @foreach($trainingYears ?? [] as $trainingYear)
                                <option value="{{ $trainingYear->id }}" {{ old('training_year_id', $selectedTrainingYearId ?? '') == $trainingYear->id ? 'selected' : '' }}>
                                    {{ $trainingYear->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">Tanggal Masuk</label>
                        <div class="relative">
                            <input type="text" id="entry_date_picker" name="entry_date" value="{{ old('entry_date') }}" placeholder="dd/mm/yyyy"
                                class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-data="{ classroom: '{{ old('classroom', ($type ?? 'reguler') === 'karyawan' ? 'Karyawan' : 'Reguler') }}' }">
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Kelas <span class="text-red-400">*</span>
                        </label>
                        <select name="classroom" x-model="classroom" required class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all appearance-none">
                            <option value="">Pilih Kelas</option>
                            <option value="Reguler">Kelas Reguler</option>
                            <option value="Karyawan">Kelas Karyawan</option>
                        </select>
                        {{-- Hidden type field that syncs with classroom selection --}}
                        <input type="hidden" name="type" :value="classroom === 'Karyawan' ? 'karyawan' : 'reguler'">
                    </div>

                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                            Tipe Kelas
                        </label>
                        <div class="w-full bg-[#0b1221] border border-[#1e293b] text-gray-400 rounded-xl px-4 py-3" x-text="classroom === 'Karyawan' ? 'Karyawan' : (classroom === 'Reguler' ? 'Reguler' : '-')">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Tipe kelas otomatis berdasarkan kelas yang dipilih</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">Jenis Kelamin</label>
                        <select name="gender" class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all appearance-none">
                            <option value="" {{ old('gender') === null ? 'selected' : '' }}>-</option>
                            <option value="Laki-laki" {{ old('gender') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">No Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place') }}" placeholder="Kota Lahir"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all">
                    </div>

                    <div>
                        <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">Tanggal Lahir</label>
                        <div class="relative">
                            <input type="text" id="birth_date_picker" name="birth_date" value="{{ old('birth_date') }}" placeholder="dd/mm/yyyy"
                                class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">Alamat Lengkap</label>
                    <textarea name="address" rows="3" placeholder="Jl. Raya..."
                        class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all resize-none">{{ old('address') }}</textarea>
                </div>

                {{-- Footer Actions --}}
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 border-t border-[#1e293b] pt-6">
                    <a href="{{ route('admin.classes.' . ($type ?? 'reguler'), ['year' => $selectedYearId ?? '']) }}" 
                        class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                        BATAL
                    </a>
                    <button type="submit" 
                        class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                        SIMPAN SISWA
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Initialize Flatpickr date pickers
document.addEventListener('DOMContentLoaded', function() {
    const flatpickrConfig = {
        dateFormat: "d/m/Y",
        allowInput: true,
        locale: {
            firstDayOfWeek: 1,
            weekdays: {
                shorthand: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                longhand: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
            },
            months: {
                shorthand: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                longhand: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']
            }
        },
        theme: "dark",
        disableMobile: true
    };
    
    flatpickr("#entry_date_picker", flatpickrConfig);
    flatpickr("#birth_date_picker", flatpickrConfig);
});
</script>
@endpush