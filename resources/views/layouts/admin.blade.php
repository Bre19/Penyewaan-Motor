<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Admin Panel - BaliMotor' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-bali-soft text-bali-slate antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[290px_1fr]">
        <aside class="border-r border-white/10 bg-bali-navy text-white">
            <div class="sticky top-0 flex min-h-screen flex-col p-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-sm font-black text-bali-navy shadow-xl">
                        BM
                    </span>
                    <div>
                        <strong class="block text-lg leading-none">BaliMotor</strong>
                        <span class="mt-1 block text-xs font-bold text-slate-400">Operational Admin</span>
                    </div>
                </a>

                <div class="mt-8 rounded-[1.5rem] border border-white/10 bg-white/10 p-5">
                    <span class="text-xs font-black uppercase tracking-[0.18em] text-bali-teal">Status Sistem</span>
                    <strong class="mt-2 block text-lg">Rental Control Center</strong>
                    <p class="mt-2 text-xs leading-6 text-slate-400">
                        Kelola booking, pembayaran, dan operasional penyewaan motor.
                    </p>
                </div>

                <nav class="mt-8 grid gap-2">
                    <a href="{{ route('admin.dashboard') }}"
                       class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'bg-white text-bali-navy shadow-xl' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('admin.bookings.index') }}"
                       class="admin-nav-link {{ request()->routeIs('admin.bookings.*') ? 'bg-white text-bali-navy shadow-xl' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        Verifikasi Booking
                    </a>

                    <a href="{{ route('admin.payments.index') }}"
                       class="admin-nav-link {{ request()->routeIs('admin.payments.*') ? 'bg-white text-bali-navy shadow-xl' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        Verifikasi Pembayaran
                    </a>

                    <a href="{{ route('motorcycles.index') }}"
                       class="admin-nav-link text-slate-300 hover:bg-white/10 hover:text-white">
                        Lihat Website
                    </a>
                </nav>

                <div class="mt-auto rounded-[1.5rem] border border-white/10 bg-white/10 p-5">
                    <span class="block text-xs font-black uppercase tracking-wide text-slate-400">Login sebagai</span>
                    <strong class="mt-2 block text-sm">{{ auth()->user()->name }}</strong>
                    <span class="mt-1 block truncate text-xs text-slate-400">{{ auth()->user()->email }}</span>

                    <form method="POST" action="{{ route('logout') }}" class="mt-5">
                        @csrf
                        <button type="submit" class="w-full rounded-full bg-white px-4 py-3 text-sm font-black text-bali-navy transition hover:bg-slate-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="min-w-0">
            <header class="sticky top-0 z-40 border-b border-bali-line bg-white/90 backdrop-blur-2xl">
                <div class="flex h-20 items-center justify-between px-6 lg:px-10">
                    <div>
                        <span class="text-xs font-black uppercase tracking-[0.18em] text-bali-teal">Admin BaliMotor</span>
                        <h1 class="mt-1 text-xl font-black text-bali-navy">@yield('page-title', 'Dashboard')</h1>
                    </div>

                    <a href="{{ route('home') }}" class="btn-light px-5 py-3">
                        Website
                    </a>
                </div>
            </header>

            <div class="p-6 lg:p-10">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>