<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'BaliMotor') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-bali-navy via-slate-900 to-blue-950 text-bali-slate antialiased">
    <div class="flex min-h-screen items-center justify-center px-6 py-12">
        <div class="w-full max-w-lg">
            <div class="mb-8 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 font-extrabold text-white">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-bali-teal to-bali-orange text-sm font-black text-white">
                        BM
                    </span>
                    <span class="text-2xl">BaliMotor</span>
                </a>

                <p class="mt-3 text-sm text-slate-300">
                    Masuk atau daftar untuk mengajukan penyewaan motor.
                </p>
            </div>

            <div class="rounded-[1.7rem] bg-white p-8 shadow-2xl">
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        </div>
    </div>
</body>
</html>