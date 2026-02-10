<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - LPK Garuda Bakti</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- Flatpickr Date Picker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0b1221; color: #f3f4f6; }
        [x-cloak] { display: none !important; }
        /* Smooth transitions */
        * { scroll-behavior: smooth; }
        main { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <nav class="bg-[#0b1221] border-b border-[#1e293b] sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center mr-10">
                        <a href="{{ route('admin.dashboard') }}">
                            <img class="h-12 w-auto object-contain" src="{{ asset('images/logo_lpk.jpeg') }}" alt="Logo LPK">
                        </a>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:flex h-full items-center">
                        
                        <a href="{{ route('admin.dashboard') }}" 
                           class="{{ request()->routeIs('admin.dashboard') ? 'text-[#d4af37] border-b-2 border-[#d4af37]' : 'text-gray-400 hover:text-[#d4af37] border-b-2 border-transparent' }} px-1 pt-1 text-sm font-bold transition duration-150 h-full flex items-center">
                            Dashboard
                        </a>

                        <a href="{{ route('admin.training_years.index') }}" 
                           class="{{ request()->routeIs('admin.training_years*') || request()->routeIs('admin.training_batches*') ? 'text-[#d4af37] border-b-2 border-[#d4af37]' : 'text-gray-400 hover:text-[#d4af37] border-b-2 border-transparent' }} px-1 pt-1 text-sm font-bold transition duration-150 h-full flex items-center">
                            Periode Pelatihan
                        </a>
                        
                        <a href="{{ route('admin.instructors.index') }}" 
                           class="{{ request()->routeIs('admin.instructors*') ? 'text-[#d4af37] border-b-2 border-[#d4af37]' : 'text-gray-400 hover:text-[#d4af37] border-b-2 border-transparent' }} px-1 pt-1 text-sm font-bold transition duration-150 h-full flex items-center">
                            Instruktur
                        </a>

                        <div class="relative h-full flex items-center" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" 
                                    class="{{ request()->routeIs('admin.classes*') || request()->is('admin/classes*') ? 'text-[#d4af37] border-b-2 border-[#d4af37]' : 'text-gray-400 hover:text-[#d4af37] border-b-2 border-transparent' }} inline-flex items-center px-1 pt-1 text-sm font-bold focus:outline-none h-full transition duration-150">
                                <span>Kelas</span>
                                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute top-16 left-0 w-48 rounded-md shadow-lg bg-[#0f172a] border border-[#1e293b] ring-1 ring-black ring-opacity-5 py-1 z-50" 
                                 style="display: none;">
                                
                                <a href="{{ route('admin.classes.reguler') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37]">
                                    Kelas Reguler
                                </a>
                                <a href="{{ route('admin.classes.karyawan') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37]">
                                    Kelas Karyawan
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('admin.news.index') }}" 
                           class="{{ request()->routeIs('admin.news*') ? 'text-[#d4af37] border-b-2 border-[#d4af37]' : 'text-gray-400 hover:text-[#d4af37] border-b-2 border-transparent' }} px-1 pt-1 text-sm font-bold transition duration-150 h-full flex items-center">
                            Berita & Info
                        </a>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center text-sm font-medium text-gray-300 hover:text-[#d4af37] focus:outline-none transition">
                            <span class="mr-3 text-right">
                                <span class="block text-[10px] text-[#d4af37] font-bold uppercase tracking-wider">Administrator</span>
                                <span class="block font-semibold leading-tight">{{ Auth::user()->name }}</span>
                            </span>
                            <div class="h-9 w-9 rounded-full bg-[#1e293b] flex items-center justify-center border border-[#d4af37]">
                                <svg class="h-5 w-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        </button>

                        <div x-show="open" 
                             class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-[#0f172a] border border-[#1e293b] py-1 z-50" 
                             style="display: none;">
                            <a href="{{ route('admin.profile.edit') }}" class="block w-full px-4 py-2 text-left text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37] transition">
                                Profil Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37] transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow py-8 page-transition">
        @yield('content')
    </main>

    <footer class="bg-[#0b1221] border-t border-[#1e293b] py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} LPK Garuda Bakti Internasional.
            </p>
        </div>
    </footer>

    {{-- Global Delete Modal (Single instance for all delete operations) --}}
    <x-global-delete-modal />

    @stack('scripts')
</body>
</html>