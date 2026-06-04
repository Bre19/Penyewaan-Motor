<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'BaliMotor - Penyewaan Motor Bali' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bali-soft text-bali-slate antialiased">
    <header class="sticky top-0 z-50 border-b border-white/70 bg-white/85 backdrop-blur-2xl">
        <div class="container-page flex h-20 items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-bali-navy text-sm font-black text-white shadow-xl shadow-slate-900/20 transition group-hover:rotate-3">
                    BM
                </span>
                <div class="leading-none">
                    <strong class="block text-xl font-black tracking-tight text-bali-navy">BaliMotor</strong>
                    <span class="mt-1 block text-xs font-bold text-bali-muted">Motor Rental Bali</span>
                </div>
            </a>

            <nav class="hidden items-center gap-7 text-sm font-bold text-bali-muted lg:flex">
                <a href="{{ route('home') }}" class="transition hover:text-bali-navy">Beranda</a>
                <a href="{{ route('motorcycles.index') }}" class="transition hover:text-bali-navy">Katalog</a>
                <a href="{{ route('home') }}#cara-sewa" class="transition hover:text-bali-navy">Cara Sewa</a>
                <a href="{{ route('home') }}#keunggulan" class="transition hover:text-bali-navy">Keunggulan</a>
                <a href="{{ route('home') }}#kontak" class="transition hover:text-bali-navy">Kontak</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hidden rounded-full bg-slate-100 px-5 py-3 text-sm font-black text-bali-navy transition hover:bg-slate-200 sm:inline-flex">
                            Admin Panel
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="hidden rounded-full bg-slate-100 px-5 py-3 text-sm font-black text-bali-navy transition hover:bg-slate-200 sm:inline-flex">
                            Dashboard
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-dark px-5 py-3">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hidden rounded-full px-5 py-3 text-sm font-black text-bali-navy transition hover:bg-slate-100 sm:inline-flex">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn-dark px-5 py-3">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer id="kontak" class="border-t border-bali-line bg-white">
        <div class="container-page grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-[1.25fr_0.75fr_0.75fr_1fr]">
            <div>
                <a href="{{ route('home') }}" class="mb-5 flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-bali-navy text-sm font-black text-white">
                        BM
                    </span>
                    <div>
                        <strong class="block text-xl font-black text-bali-navy">BaliMotor</strong>
                        <span class="text-xs font-bold text-bali-muted">Motor Rental Bali</span>
                    </div>
                </a>
                <p class="max-w-sm text-sm leading-7 text-bali-muted">
                    Platform penyewaan motor untuk membantu penyewa melihat motor, mengajukan sewa,
                    melakukan pembayaran, dan memantau status secara online.
                </p>
            </div>

            <div>
                <h4 class="mb-4 font-black text-bali-navy">Menu</h4>
                <div class="grid gap-3 text-sm font-semibold text-bali-muted">
                    <a href="{{ route('home') }}" class="hover:text-bali-navy">Beranda</a>
                    <a href="{{ route('motorcycles.index') }}" class="hover:text-bali-navy">Katalog Motor</a>
                    <a href="{{ route('home') }}#cara-sewa" class="hover:text-bali-navy">Cara Sewa</a>
                </div>
            </div>

            <div>
                <h4 class="mb-4 font-black text-bali-navy">Layanan</h4>
                <div class="grid gap-3 text-sm font-semibold text-bali-muted">
                    <span>Booking Online</span>
                    <span>Verifikasi Admin</span>
                    <span>Upload Pembayaran</span>
                    <span>Pengantaran Motor</span>
                </div>
            </div>

            <div class="rounded-[1.5rem] bg-slate-100 p-6">
                <h4 class="mb-4 font-black text-bali-navy">Kontak</h4>
                <div class="grid gap-3 text-sm font-semibold text-bali-muted">
                    <span>WhatsApp: 0812-3456-7890</span>
                    <span>Area: Bali</span>
                    <span>Email: admin@balimotor.test</span>
                </div>
            </div>
        </div>

        <div class="container-page flex border-t border-bali-line py-6 text-sm font-semibold text-bali-muted">
            <span>&copy; {{ date('Y') }} BaliMotor. All rights reserved.</span>
        </div>
    </footer>
</body>
</html>