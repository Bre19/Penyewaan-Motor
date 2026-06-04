<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'BaliMotor - Penyewaan Motor Bali' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bali-soft text-bali-slate antialiased">
    <header class="sticky top-0 z-50 border-b border-bali-line bg-white/90 backdrop-blur-xl">
        <div class="mx-auto flex h-20 w-[min(1180px,92%)] items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3 font-extrabold text-bali-navy">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-bali-teal to-bali-orange text-sm font-black text-white">
                    BM
                </span>
                <span class="text-xl">BaliMotor</span>
            </a>

            <nav class="hidden items-center gap-8 text-sm font-semibold text-bali-muted lg:flex">
                <a href="{{ route('home') }}" class="hover:text-bali-navy">Beranda</a>
                <a href="{{ route('motorcycles.index') }}" class="hover:text-bali-navy">Motor</a>
                <a href="{{ route('home') }}#cara-sewa" class="hover:text-bali-navy">Cara Sewa</a>
                <a href="{{ route('home') }}#keunggulan" class="hover:text-bali-navy">Keunggulan</a>
                <a href="{{ route('home') }}#kontak" class="hover:text-bali-navy">Kontak</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-full px-5 py-3 text-sm font-bold text-bali-navy hover:bg-slate-100">
                        Dashboard
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-bali-navy px-5 py-3 text-sm font-bold text-white hover:bg-bali-slate">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-full px-5 py-3 text-sm font-bold text-bali-navy hover:bg-slate-100">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="rounded-full bg-bali-navy px-5 py-3 text-sm font-bold text-white hover:bg-bali-slate">
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
        <div class="mx-auto grid w-[min(1180px,92%)] gap-10 py-14 md:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="mb-4 flex items-center gap-3 font-extrabold text-bali-navy">
                    <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-bali-teal to-bali-orange text-sm font-black text-white">
                        BM
                    </span>
                    <span class="text-xl">BaliMotor</span>
                </a>
                <p class="text-sm leading-7 text-bali-muted">
                    Website penyewaan motor di Bali untuk membantu pelanggan melihat motor, mengajukan sewa,
                    melakukan pembayaran, dan memantau status penyewaan secara online.
                </p>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-bali-navy">Menu</h4>
                <div class="grid gap-3 text-sm text-bali-muted">
                    <a href="{{ route('home') }}" class="hover:text-bali-navy">Beranda</a>
                    <a href="{{ route('motorcycles.index') }}" class="hover:text-bali-navy">Daftar Motor</a>
                    <a href="{{ route('home') }}#cara-sewa" class="hover:text-bali-navy">Cara Sewa</a>
                </div>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-bali-navy">Layanan</h4>
                <div class="grid gap-3 text-sm text-bali-muted">
                    <span>Booking Motor Online</span>
                    <span>Pembayaran Cash/Digital</span>
                    <span>Pengantaran Motor</span>
                </div>
            </div>

            <div>
                <h4 class="mb-4 font-bold text-bali-navy">Kontak</h4>
                <div class="grid gap-3 text-sm text-bali-muted">
                    <span>WhatsApp: 0812-3456-7890</span>
                    <span>Area: Bali</span>
                    <span>Email: admin@balimotor.test</span>
                </div>
            </div>
        </div>

        <div class="mx-auto flex w-[min(1180px,92%)] border-t border-bali-line py-6 text-sm text-bali-muted">
            <span>&copy; {{ date('Y') }} BaliMotor. All rights reserved.</span>
        </div>
    </footer>
</body>
</html>