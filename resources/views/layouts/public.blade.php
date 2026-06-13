<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'BaliMotor - Penyewaan Motor Bali' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bali-soft text-bali-slate antialiased">
    <header class="site-header">
        <div class="container-page flex h-20 items-center justify-between gap-6">
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-bali-navy text-sm font-black text-white shadow-clean transition group-hover:-rotate-3">
                    BM
                </span>

                <div class="leading-none">
                    <strong class="block text-xl font-black tracking-tight text-bali-ink">
                        BaliMotor
                    </strong>
                    <span class="mt-1 block text-xs font-bold text-bali-muted">
                        Motor Rental Bali
                    </span>
                </div>
            </a>

            <nav class="hidden items-center gap-1 lg:flex">
                <a href="{{ route('home') }}" class="site-nav-link">Beranda</a>
                <a href="{{ route('motorcycles.index') }}" class="site-nav-link">Katalog</a>
                <a href="{{ route('home') }}#cara-sewa" class="site-nav-link">Cara Sewa</a>
                <!-- <a href="{{ route('home') }}#keunggulan" class="site-nav-link">Keunggulan</a> -->
                <a href="{{ route('home') }}#kontak" class="site-nav-link">Kontak</a>
            </nav>

            <div class="hidden items-center gap-3 md:flex">
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn-light px-5 py-3">
                            Admin Panel
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn-light px-5 py-3">
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
                    <a href="{{ route('login') }}" class="site-nav-link">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="btn-dark px-5 py-3">
                        Register
                    </a>
                @endauth
            </div>

            <details class="relative md:hidden">
                <summary class="flex h-11 w-11 cursor-pointer list-none items-center justify-center rounded-2xl border border-bali-line bg-white text-lg font-black text-bali-navy shadow-clean">
                    ☰
                </summary>

                <div class="absolute right-0 mt-3 w-72 rounded-[1.5rem] border border-bali-line bg-white p-4 shadow-2xl">
                    <nav class="grid gap-2 text-sm font-bold">
                        <a href="{{ route('home') }}" class="rounded-2xl px-4 py-3 text-bali-muted hover:bg-slate-100 hover:text-bali-navy">Beranda</a>
                        <a href="{{ route('motorcycles.index') }}" class="rounded-2xl px-4 py-3 text-bali-muted hover:bg-slate-100 hover:text-bali-navy">Katalog</a>
                        <a href="{{ route('home') }}#cara-sewa" class="rounded-2xl px-4 py-3 text-bali-muted hover:bg-slate-100 hover:text-bali-navy">Cara Sewa</a>
                        <!-- <a href="{{ route('home') }}#keunggulan" class="rounded-2xl px-4 py-3 text-bali-muted hover:bg-slate-100 hover:text-bali-navy">Keunggulan</a> -->
                    </nav>

                    <div class="mt-4 border-t border-bali-line pt-4">
                        @auth
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="btn-light w-full">
                                    Admin Panel
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="btn-light w-full">
                                    Dashboard
                                </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                                @csrf
                                <button type="submit" class="btn-dark w-full">
                                    Logout
                                </button>
                            </form>
                        @else
                            <div class="grid gap-3">
                                <a href="{{ route('login') }}" class="btn-light w-full">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="btn-dark w-full">
                                    Register
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </details>
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
                        <strong class="block text-xl font-black text-bali-ink">
                            BaliMotor
                        </strong>
                        <span class="text-xs font-bold text-bali-muted">
                            Motor Rental Bali
                        </span>
                    </div>
                </a>

                <p class="max-w-sm text-sm leading-7 text-bali-muted">
                    Platform penyewaan motor di Bali untuk booking online, verifikasi dokumen,
                    pembayaran digital, pengantaran unit, dan pemantauan status sewa.
                </p>
            </div>

            <div>
                <h4 class="mb-4 font-black text-bali-ink">Menu</h4>
                <div class="grid gap-3 text-sm font-semibold text-bali-muted">
                    <a href="{{ route('home') }}" class="hover:text-bali-navy">Beranda</a>
                    <a href="{{ route('motorcycles.index') }}" class="hover:text-bali-navy">Katalog Motor</a>
                    <a href="{{ route('home') }}#cara-sewa" class="hover:text-bali-navy">Cara Sewa</a>
                    <!-- <a href="{{ route('home') }}#keunggulan" class="hover:text-bali-navy">Keunggulan</a> -->
                </div>
            </div>

            <div>
                <h4 class="mb-4 font-black text-bali-ink">Layanan</h4>
                <div class="grid gap-3 text-sm font-semibold text-bali-muted">
                    <span>Booking Online</span>
                    <span>Verifikasi Penyewa</span>
                    <span>Upload Pembayaran</span>
                    <span>Safety Score</span>
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-bali-line bg-bali-soft p-6">
                <h4 class="mb-4 font-black text-bali-ink">Kontak</h4>
                <div class="grid gap-3 text-sm font-semibold text-bali-muted">
                    <span>WhatsApp: 0812-3456-7890</span>
                    <span>Area layanan: Bali</span>
                    <span>Email: admin@balimotor.test</span>
                </div>
            </div>
        </div>

        <div class="container-page flex flex-col gap-2 border-t border-bali-line py-6 text-sm font-semibold text-bali-muted md:flex-row md:items-center md:justify-between">
            <span>&copy; {{ date('Y') }} BaliMotor. All rights reserved.</span>
            <span>Online rental management system.</span>
        </div>
    </footer>
</body>
</html>