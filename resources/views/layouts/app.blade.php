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

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0b1221; color: #f3f4f6; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <nav class="bg-[#0b1221] border-b border-[#1e293b] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center mr-10">
                        <a href="{{ route('admin.dashboard') }}">
                            <img class="h-12 w-auto object-contain" src="{{ asset('images/logo_lpk.jpeg') }}" alt="Logo LPK">
                        </a>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:flex h-full items-center">
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            Dashboard
                        </x-nav-link>
                        
                        <x-nav-link :href="route('admin.instruktur.index')" :active="request()->routeIs('admin.instruktur*')">
                            Instruktur
                        </x-nav-link>

                        <x-nav-link :href="route('admin.students.index')" :active="request()->routeIs('admin.students*')">
                            Siswa
                        </x-nav-link>

                        <x-nav-link :href="route('admin.classes.index')" :active="request()->routeIs('admin.classes*')">
                            Kelas
                        </x-nav-link>

                        <x-nav-link :href="route('admin.news.index')" :active="request()->routeIs('admin.news*')">
                            Berita & Info
                        </x-nav-link>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-300 hover:text-[#d4af37] focus:outline-none transition duration-150 ease-in-out">
                            <span class="mr-2 text-right">
                                <span class="block text-xs text-[#d4af37] font-bold uppercase tracking-wider">Administrator</span>
                                <span class="block font-semibold">{{ Auth::user()->name }}</span>
                            </span>
                            <div class="h-9 w-9 rounded-full bg-[#1e293b] flex items-center justify-center border border-[#d4af37]">
                                <svg class="h-5 w-5 text-[#d4af37]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-[#0f172a] ring-1 ring-black ring-opacity-5 py-1 text-gray-300 border border-[#1e293b] z-50" style="display: none;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm hover:bg-[#1e293b] hover:text-[#d4af37] transition">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow py-8">
        {{ $slot }}
    </main>

    <footer class="bg-[#0b1221] border-t border-[#1e293b] py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} LPK Garuda Bakti Internasional. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>