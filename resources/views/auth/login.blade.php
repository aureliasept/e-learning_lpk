<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Learning System</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b1221;
        }
    </style>
</head>
<body class="antialiased text-gray-100">

    <div class="min-h-screen flex flex-col justify-center items-center px-4 bg-gradient-to-b from-[#0b1221] to-[#111827]">
        
        <div class="w-full max-w-[380px] bg-[#0f172a] border border-[#d4af37] rounded-2xl shadow-[0_0_20px_rgba(212,175,55,0.1)] p-6 relative overflow-hidden">
            
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-[#d4af37] opacity-10 blur-2xl rounded-full pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-[#d4af37] opacity-10 blur-2xl rounded-full pointer-events-none"></div>

            <div class="text-center mb-6 relative z-10">
                
                <div class="mx-auto mb-4 flex justify-center items-center px-4">
                    <img src="{{ asset('images/logo_lpk.jpeg') }}" 
                         alt="Logo LPK Garuda Bakti" 
                         class="w-full max-w-[180px] h-auto object-contain drop-shadow-lg rounded-md">
                </div>
                
                <h1 class="text-xl font-bold text-white tracking-wide uppercase">
                    E-LEARNING SYSTEM
                </h1>
                <p class="mt-1 text-[10px] font-bold text-[#d4af37] tracking-widest uppercase">
                    LPK GARUDA BAKTI INTERNASIONAL
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-900/20 border border-red-500/50 rounded p-3">
                    <div class="font-medium text-red-400 text-xs">
                        {{ __('Login Gagal.') }}
                    </div>
                    <ul class="mt-1 list-disc list-inside text-[10px] text-red-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="relative z-10 space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-medium text-gray-300 mb-1.5">
                        Alamat Email
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                        placeholder="Masukan email terdaftar"
                        class="block w-full rounded border border-[#d4af37] bg-[#0b1221] text-white px-3 py-2.5 text-xs placeholder-gray-500 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition shadow-sm"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-medium text-gray-300 mb-1.5">
                        Kata Sandi
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••"
                        class="block w-full rounded border border-[#d4af37] bg-[#0b1221] text-white px-3 py-2.5 text-xs placeholder-gray-500 focus:border-[#d4af37] focus:ring-1 focus:ring-[#d4af37] focus:outline-none transition shadow-sm"
                    >
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-[#d4af37] bg-[#0b1221] text-[#d4af37] shadow-sm focus:ring-offset-0 focus:ring-1 focus:ring-[#d4af37] h-3.5 w-3.5" name="remember">
                        <span class="ml-2 text-xs text-gray-300">{{ __('Ingat Saya') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-xs text-[#d4af37] hover:text-[#eebb4d] hover:underline transition" href="{{ route('password.request') }}">
                            {{ __('Lupa Password?') }}
                        </a>
                    @endif
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded shadow-sm text-xs font-bold text-[#0b1221] bg-[#d4af37] hover:bg-[#c5a046] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#d4af37] focus:ring-offset-[#0f172a] transition duration-200 uppercase tracking-wider">
                        {{ __('Masuk Sekarang') }}
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 text-center text-[10px] text-gray-600">
            &copy; {{ date('Y') }} LPK Garuda Bakti Internasional.
        </div>

    </div>
</body>
</html>