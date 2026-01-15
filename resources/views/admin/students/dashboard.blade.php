<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peserta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 900: '#0b1220', 800: '#111b2e', 700: '#1a2639' },
                        gold: { 500: '#d4af37', 600: '#b8962e' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-navy-900 text-gray-200">
    <nav class="bg-navy-900 border-b border-navy-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-gold-500 font-bold text-xl">GBI STUDENT</span>
                </div>
                <div class="flex items-center">
                    <span class="mr-4">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm bg-red-600 px-3 py-1 rounded hover:bg-red-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <h2 class="text-xl font-bold text-white mb-4 border-l-4 border-gold-500 pl-3">Kursus Saya</h2>
                @forelse($enrollments as $item)
                    <div class="bg-navy-800 p-5 rounded-lg border border-navy-700 mb-4">
                        <h3 class="font-bold text-white">{{ $item->course->title ?? 'Judul Kursus' }}</h3>
                        <p class="text-sm text-gray-400">Instruktur: {{ $item->course->teacher->user->name ?? '-' }}</p>
                        <a href="#" class="mt-3 inline-block text-sm text-gold-500 hover:text-gold-600">Lanjut Belajar &rarr;</a>
                    </div>
                @empty
                    <div class="text-gray-500">Belum ada kursus yang diikuti.</div>
                @endforelse
            </div>

            <div>
                <h2 class="text-xl font-bold text-white mb-4 border-l-4 border-gold-500 pl-3">Pengumuman</h2>
                <div class="bg-navy-800 rounded-lg border border-navy-700 divide-y divide-navy-700">
                    @forelse($news as $item)
                        <div class="p-4">
                            <h4 class="font-bold text-white text-sm">{{ $item->title }}</h4>
                            <span class="text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="p-4 text-gray-500 text-sm">Tidak ada pengumuman.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>