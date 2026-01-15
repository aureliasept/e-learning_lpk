<nav x-data="{ open: false }" class="bg-[#0f172a] border-b border-gray-700 fixed w-full z-50 transition-all duration-300 h-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <span class="text-xl font-bold text-[#c9a341] tracking-wider">GBI SYSTEM</span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    
                    {{-- ================================================= --}}
                    {{-- MENU ADMIN --}}
                    {{-- ================================================= --}}
                    @if(Auth::user()->role === 'admin')
                        {{-- 1. Dashboard --}}
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.dashboard') ? 'border-[#c9a341] text-[#c9a341]' : 'border-transparent text-gray-400 hover:text-white hover:border-gray-300' }}">
                            Dashboard
                        </a>

                        {{-- 2. DROPDOWN KELAS / SISWA (FITUR YANG DIKEMBALIKAN) --}}
                        <div class="hidden sm:flex sm:items-center">
                            <div class="relative" x-data="{ openDropdown: false }" @click.away="openDropdown = false">
                                <button @click="openDropdown = !openDropdown" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-400 hover:text-white hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out">
                                    <span>Kelas & Siswa</span>
                                    <svg class="ml-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                
                                {{-- Isi Dropdown --}}
                                <div x-show="openDropdown" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-[#1e293b] ring-1 ring-black ring-opacity-5 z-50" 
                                     style="display: none;">
                                    
                                    <a href="{{ route('admin.students.index') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#0f172a] hover:text-[#c9a341]">
                                        Semua Kelas
                                    </a>
                                    <a href="{{ route('admin.students.showByType', 'Reguler') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#0f172a] hover:text-[#c9a341]">
                                        Kelas Reguler
                                    </a>
                                    <a href="{{ route('admin.students.showByType', 'Karyawan') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#0f172a] hover:text-[#c9a341]">
                                        Kelas Karyawan
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Instruktur --}}
                        <a href="{{ route('admin.instruktur.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.instruktur.*') ? 'border-[#c9a341] text-[#c9a341]' : 'border-transparent text-gray-400 hover:text-white hover:border-gray-300' }}">
                            Instruktur
                        </a>

                        {{-- 4. Berita --}}
                        <a href="{{ route('admin.news.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('admin.news.*') ? 'border-[#c9a341] text-[#c9a341]' : 'border-transparent text-gray-400 hover:text-white hover:border-gray-300' }}">
                            Berita
                        </a>
                    
                    {{-- ================================================= --}}
                    {{-- MENU INSTRUKTUR --}}
                    {{-- ================================================= --}}
                    @elseif(Auth::user()->role === 'instructor')
                        <a href="{{ route('instructor.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('instructor.dashboard') ? 'border-[#c9a341] text-[#c9a341]' : 'border-transparent text-gray-400 hover:text-white hover:border-gray-300' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('instructor.modules.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('instructor.modules.*') ? 'border-[#c9a341] text-[#c9a341]' : 'border-transparent text-gray-400 hover:text-white hover:border-gray-300' }}">
                            Modul & Materi
                        </a>
                        <a href="{{ route('instructor.schedules.create') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('instructor.schedules.*') ? 'border-[#c9a341] text-[#c9a341]' : 'border-transparent text-gray-400 hover:text-white hover:border-gray-300' }}">
                            Jadwal Mengajar
                        </a>
                        <a href="#" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out border-transparent text-gray-400 hover:text-white hover:border-gray-300">
                            Quiz
                        </a>

                    {{-- ================================================= --}}
                    {{-- MENU SISWA --}}
                    {{-- ================================================= --}}
                    @else
                        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('student.dashboard') ? 'border-[#c9a341] text-[#c9a341]' : 'border-transparent text-gray-400 hover:text-white hover:border-gray-300' }}">
                            Dashboard
                        </a>
                    @endif

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
                    <div @click="open = ! open">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-400 bg-[#1e293b] hover:text-[#c9a341] focus:outline-none transition ease-in-out duration-150">
                            <div class="uppercase font-bold">{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </div>

                    <div x-show="open" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-[#1e293b] ring-1 ring-black ring-opacity-5" style="display: none;">
                        <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-700">
                            {{ ucfirst(Auth::user()->role) }}
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#0f172a] hover:text-[#c9a341]">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-red-400 hover:bg-[#0f172a] hover:text-red-500">
                                Log Out
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#1e293b]">
        <div class="pt-2 pb-3 space-y-1">
            
            {{-- LOGIKA ROLE UNTUK MOBILE --}}
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-[#c9a341] text-base font-medium text-[#c9a341] bg-[#0f172a]">Dashboard</a>
                
                {{-- Dropdown Siswa versi Mobile (Dibuka langsung) --}}
                <div class="pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-400">
                    <span class="text-xs uppercase font-bold text-gray-500 mb-2 block">Kelas & Siswa</span>
                    <a href="{{ route('admin.students.index') }}" class="block py-1 pl-4 hover:text-white">Semua Kelas</a>
                    <a href="{{ route('admin.students.showByType', 'Reguler') }}" class="block py-1 pl-4 hover:text-white">Kelas Reguler</a>
                    <a href="{{ route('admin.students.showByType', 'Karyawan') }}" class="block py-1 pl-4 hover:text-white">Kelas Karyawan</a>
                </div>

                <a href="{{ route('admin.instruktur.index') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Instruktur</a>
                <a href="{{ route('admin.news.index') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Berita</a>
            
            @elseif(Auth::user()->role === 'instructor')
                <a href="{{ route('instructor.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-[#c9a341] text-base font-medium text-[#c9a341] bg-[#0f172a]">Dashboard</a>
                <a href="{{ route('instructor.modules.index') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Modul</a>
                <a href="{{ route('instructor.schedules.create') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700">Jadwal</a>
            
            @else
                <a href="{{ route('student.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-[#c9a341] text-base font-medium text-[#c9a341] bg-[#0f172a]">Dashboard</a>
            @endif

        </div>

        <div class="pt-4 pb-1 border-t border-gray-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-red-400 hover:text-red-500 hover:bg-gray-700 transition duration-150 ease-in-out">
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>