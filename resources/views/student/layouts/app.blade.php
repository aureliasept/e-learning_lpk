<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Peserta') - LPK Garuda Bakti</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0b1221; color: #f3f4f6; }
        [x-cloak] { display: none !important; }
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
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3">
                        <img class="h-12 w-auto object-contain" src="{{ asset('images/logo_lpk.jpeg') }}" alt="Logo LPK">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:flex h-full items-center">
                    <a href="{{ route('student.dashboard') }}" 
                       class="{{ request()->routeIs('student.dashboard') ? 'text-[#d4af37] border-b-2 border-[#d4af37]' : 'text-gray-400 hover:text-[#d4af37] border-b-2 border-transparent' }} px-1 pt-1 text-sm font-bold transition duration-150 h-full flex items-center">
                        Dashboard
                    </a>
                    
                    <a href="{{ route('student.instructions.index') }}" 
                       class="{{ request()->routeIs('student.instructions*') ? 'text-[#d4af37] border-b-2 border-[#d4af37]' : 'text-gray-400 hover:text-[#d4af37] border-b-2 border-transparent' }} px-1 pt-1 text-sm font-bold transition duration-150 h-full flex items-center">
                        Papan Instruksi
                    </a>

                    <a href="{{ route('student.exam.index') }}" 
                       class="{{ request()->routeIs('student.exam*') ? 'text-[#d4af37] border-b-2 border-[#d4af37]' : 'text-gray-400 hover:text-[#d4af37] border-b-2 border-transparent' }} px-1 pt-1 text-sm font-bold transition duration-150 h-full flex items-center">
                        Quiz
                    </a>

                    <a href="{{ route('student.news.index') }}" 
                       class="{{ request()->routeIs('student.news*') ? 'text-[#d4af37] border-b-2 border-[#d4af37]' : 'text-gray-400 hover:text-[#d4af37] border-b-2 border-transparent' }} px-1 pt-1 text-sm font-bold transition duration-150 h-full flex items-center">
                        Berita
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center text-sm font-medium text-gray-300 hover:text-[#d4af37] focus:outline-none transition">
                        <span class="mr-3 text-right">
                            <span class="block text-[10px] text-[#d4af37] font-bold uppercase tracking-wider">Peserta</span>
                            <span class="block font-semibold leading-tight">{{ Auth::user()->name }}</span>
                        </span>
                        <div class="h-9 w-9 rounded-full bg-[#1e293b] flex items-center justify-center border border-[#d4af37]">
                            <svg class="h-5 w-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    </button>

                    <div x-show="open" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-[#0f172a] border border-[#1e293b] py-1 z-50" style="display: none;">
                        <a href="{{ route('student.profile.edit') }}" class="block w-full px-4 py-2 text-left text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37] transition">
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

            {{-- Mobile menu button --}}
            <div class="flex items-center sm:hidden" x-data="{ mobileOpen: false }">
                <button @click="mobileOpen = !mobileOpen" class="text-gray-400 hover:text-[#d4af37] p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                
                <div x-show="mobileOpen" @click.away="mobileOpen = false" class="absolute top-20 left-0 right-0 bg-[#0f172a] border-b border-[#1e293b] py-2 z-50" style="display: none;">
                    <a href="{{ route('student.dashboard') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37]">Dashboard</a>
                    <a href="{{ route('student.instructions.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37]">Papan Instruksi</a>
                    <a href="{{ route('student.exam.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37]">Quiz</a>
                    <a href="{{ route('student.news.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37]">Berita</a>
                    <hr class="my-2 border-[#1e293b]">
                    <a href="{{ route('student.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37]">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-300 hover:bg-[#1e293b] hover:text-[#d4af37]">Logout</button>
                    </form>
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

@stack('scripts')
</body>
</html>
