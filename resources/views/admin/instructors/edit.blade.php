@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="instructorForm()">
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
        <a href="{{ route('admin.instructors.index') }}" class="text-gray-400 hover:text-[#d4af37] transition">Instruktur</a>
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-[#d4af37] font-medium">Edit Instruktur</span>
    </nav>

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-[#d4af37] to-[#b8962e] flex items-center justify-center shadow-lg shadow-[#d4af37]/20">
                <svg class="w-6 h-6 text-[#0b1221]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white tracking-wide">Edit Instruktur</h1>
                <p class="text-sm text-gray-400">Perbarui data instruktur {{ $instructor->name }}</p>
            </div>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl shadow-2xl overflow-hidden">
        {{-- Card Header --}}
        <div class="bg-[#0b1221]/50 border-b border-[#1e293b] px-8 py-4">
            <h2 class="text-sm font-bold text-[#d4af37] uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Informasi Instruktur
            </h2>
        </div>

        @php
            $teacher = $instructor->teacher;
            $isReguler = (bool) ($teacher->is_reguler ?? false);
            $isKaryawan = (bool) ($teacher->is_karyawan ?? false);
        @endphp

        <form action="{{ route('admin.instructors.update', $instructor->id) }}" method="POST" @submit="validateForm($event)" class="p-8">
            @csrf
            @method('PUT')
            
            {{-- Nama Lengkap --}}
            <div class="mb-6">
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                    Nama Lengkap <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $instructor->name) }}" required placeholder="Contoh: Budi Santoso"
                    class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all duration-200">
                @error('name')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tempat & Tanggal Lahir --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', $teacher->birth_place ?? '') }}" placeholder="Contoh: Bandung"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all duration-200">
                </div>
                <div>
                    <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', optional($teacher?->birth_date)->format('Y-m-d')) }}"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all duration-200">
                </div>
            </div>

            {{-- Mengajar --}}
            <div class="mb-6">
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-3 tracking-wider">Kategori Kelas</label>
                <div class="flex flex-wrap gap-4">
                    <label class="flex items-center gap-3 bg-[#0b1221]/50 rounded-xl p-4 border border-[#1e293b] cursor-pointer group hover:border-[#d4af37]/50 transition-all">
                        <input type="checkbox" name="is_reguler" value="1" {{ old('is_reguler', $isReguler ? 1 : 0) ? 'checked' : '' }} 
                            class="w-5 h-5 rounded border-[#1e293b] text-[#d4af37] focus:ring-[#d4af37] bg-[#0b1221]">
                        <div>
                            <span class="text-white font-medium group-hover:text-[#d4af37] transition">Kelas Reguler</span>
                            <p class="text-xs text-gray-500">Mengajar kelas reguler</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 bg-[#0b1221]/50 rounded-xl p-4 border border-[#1e293b] cursor-pointer group hover:border-[#d4af37]/50 transition-all">
                        <input type="checkbox" name="is_karyawan" value="1" {{ old('is_karyawan', $isKaryawan ? 1 : 0) ? 'checked' : '' }} 
                            class="w-5 h-5 rounded border-[#1e293b] text-[#d4af37] focus:ring-[#d4af37] bg-[#0b1221]">
                        <div>
                            <span class="text-white font-medium group-hover:text-[#d4af37] transition">Kelas Karyawan</span>
                            <p class="text-xs text-gray-500">Mengajar kelas karyawan</p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-[#1e293b] my-8"></div>

            {{-- Chained Dropdown: Tahun → Gelombang (Periode Mengajar) --}}
            <div class="mb-6" x-data="batchDropdown()">
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-3 tracking-wider">
                    Periode Mengajar (Opsional)
                </label>
                <p class="text-xs text-gray-500 mb-4">Pilih tahun dan gelombang di mana instruktur ini akan mengajar</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-400 text-xs mb-2">Tahun Pelatihan</label>
                        <select x-model="selectedYear" @change="fetchBatches()" 
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all appearance-none">
                            <option value="">-- Tidak Ditentukan --</option>
                            @foreach($trainingYears ?? [] as $year)
                                <option value="{{ $year->id }}" {{ $selectedTrainingYearId == $year->id ? 'selected' : '' }}>
                                    {{ $year->name }}{{ $year->is_active ? ' (Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-400 text-xs mb-2">Gelombang</label>
                        <select name="training_batch_id" x-model="selectedBatch" :disabled="!selectedYear || loading"
                            class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] transition-all appearance-none disabled:opacity-50">
                            <option value="">-- Pilih Gelombang --</option>
                            <template x-for="batch in batches" :key="batch.id">
                                <option :value="batch.id" x-text="batch.name" :selected="batch.id == initialBatchId"></option>
                            </template>
                        </select>
                        <p x-show="loading" class="text-xs text-gray-500 mt-2">Memuat gelombang...</p>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-[#1e293b] my-8"></div>

            {{-- Email --}}
            <div class="mb-6">
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                    Alamat Email <span class="text-red-400">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $instructor->email) }}" required placeholder="email@contoh.com"
                    class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all duration-200">
                @error('email')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password dengan Validasi Real-time --}}
            <div class="mb-6">
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">
                    Password Baru
                </label>
                <input type="password" name="password" x-model="password" @input="validatePassword"
                    :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500/50': passwordError && password.length > 0, 'border-green-500 focus:border-green-500 focus:ring-green-500/50': !passwordError && password.length >= 8 }"
                    class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all duration-200"
                    placeholder="Kosongkan jika tidak ingin mengubah">
                {{-- Indikator Kekuatan Password --}}
                <div class="mt-3 space-y-2" x-show="password.length > 0">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 h-1.5 bg-[#1e293b] rounded-full overflow-hidden">
                            <div class="h-full transition-all duration-300 rounded-full"
                                :class="{
                                    'w-0 bg-gray-500': password.length === 0,
                                    'w-1/4 bg-red-500': password.length > 0 && password.length < 4,
                                    'w-2/4 bg-yellow-500': password.length >= 4 && password.length < 8,
                                    'w-3/4 bg-blue-500': password.length >= 8 && password.length < 12,
                                    'w-full bg-green-500': password.length >= 12
                                }">
                            </div>
                        </div>
                        <span class="text-xs font-medium"
                            :class="{
                                'text-gray-500': password.length === 0,
                                'text-red-400': password.length > 0 && password.length < 4,
                                'text-yellow-400': password.length >= 4 && password.length < 8,
                                'text-blue-400': password.length >= 8 && password.length < 12,
                                'text-green-400': password.length >= 12
                            }"
                            x-text="password.length === 0 ? '' : (password.length < 4 ? 'Sangat Lemah' : (password.length < 8 ? 'Lemah' : (password.length < 12 ? 'Cukup Kuat' : 'Kuat')))">
                        </span>
                    </div>
                    <p class="text-xs" :class="password.length >= 8 ? 'text-green-400' : 'text-gray-500'">
                        <span x-show="password.length >= 8" class="inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Password memenuhi syarat minimal 8 karakter
                        </span>
                        <span x-show="password.length > 0 && password.length < 8" class="text-yellow-400 inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Password minimal 8 karakter (<span x-text="password.length"></span>/8)
                        </span>
                    </p>
                </div>
                <p class="mt-2 text-xs text-gray-500" x-show="password.length === 0">Kosongkan jika tidak ingin mengubah password</p>
                @error('password')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jabatan --}}
            <div class="mb-8">
                <label class="block text-[#d4af37] text-xs font-bold uppercase mb-2 tracking-wider">Jabatan</label>
                <input type="text" name="position" value="{{ old('position', $teacher->position ?? '') }}" placeholder="Contoh: Head Instructor"
                    class="w-full bg-[#0b1221] border border-[#1e293b] text-white rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-[#d4af37]/50 focus:border-[#d4af37] placeholder-gray-600 transition-all duration-200">
            </div>

            {{-- Footer Actions --}}
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-4 border-t border-[#1e293b] pt-6">
                <a href="{{ route('admin.instructors.index') }}" 
                    class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                    BATAL
                </a>
                <button type="submit" 
                    class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                    UPDATE DATA
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Peringatan Password --}}
<x-alert-modal id="password-warning" type="warning" title="Peringatan Password" message="" />

@endsection

@push('scripts')
<script>
function instructorForm() {
    return {
        password: '',
        passwordError: false,
        
        validatePassword() {
            this.passwordError = this.password.length > 0 && this.password.length < 8;
        },
        
        validateForm(event) {
            // Only validate password if it's being changed
            if (this.password.length > 0 && this.password.length < 8) {
                event.preventDefault();
                this.$dispatch('open-alert-modal', {
                    id: 'password-warning',
                    title: 'Password Tidak Valid',
                    message: 'Password harus minimal 8 karakter. Saat ini password Anda hanya ' + this.password.length + ' karakter.',
                    type: 'warning'
                });
                return false;
            }
            return true;
        }
    }
}

function batchDropdown() {
    return {
        selectedYear: '{{ $selectedTrainingYearId ?? "" }}',
        selectedBatch: '{{ $selectedBatchId ?? "" }}',
        initialBatchId: '{{ $selectedBatchId ?? "" }}',
        batches: @json($trainingBatches ?? []),
        loading: false,

        fetchBatches() {
            if (!this.selectedYear) {
                this.batches = [];
                this.selectedBatch = '';
                return;
            }

            this.loading = true;
            this.selectedBatch = '';

            fetch(`/admin/api/batches-by-year/${this.selectedYear}`)
                .then(response => response.json())
                .then(data => {
                    this.batches = data;
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error fetching batches:', error);
                    this.loading = false;
                });
        }
    }
}
</script>
@endpush