<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Admin Panel - BaliMotor' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-bali-slate antialiased">
    <div class="min-h-screen lg:grid lg:grid-cols-[280px_1fr]">
        <aside class="bg-bali-navy p-6 text-white">
            <a href="{{ route('home') }}" class="mb-10 flex items-center gap-3 font-extrabold">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-bali-teal to-bali-orange text-sm font-black">
                    BM
                </span>
                <span class="text-xl">Admin</span>
            </a>

            <nav class="grid gap-2 text-sm font-bold text-slate-300">
                <a href="#" class="rounded-2xl bg-white/10 px-4 py-3 text-white">Dashboard</a>
                <a href="#" class="rounded-2xl px-4 py-3 hover:bg-white/10 hover:text-white">Data Motor</a>
                <a href="#" class="rounded-2xl px-4 py-3 hover:bg-white/10 hover:text-white">Penyewa</a>
                <a href="#" class="rounded-2xl px-4 py-3 hover:bg-white/10 hover:text-white">Pengajuan Sewa</a>
                <a href="#" class="rounded-2xl px-4 py-3 hover:bg-white/10 hover:text-white">Pembayaran</a>
                <a href="#" class="rounded-2xl px-4 py-3 hover:bg-white/10 hover:text-white">Pengantaran</a>
            </nav>
        </aside>

        <div>
            <header class="border-b border-bali-line bg-white">
                <div class="flex h-20 items-center justify-between px-6 lg:px-10">
                    <div>
                        <h1 class="text-xl font-black text-bali-navy">{{ $pageTitle ?? 'Admin Dashboard' }}</h1>
                        <p class="text-sm text-bali-muted">Kelola operasional penyewaan motor.</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-full bg-bali-navy px-5 py-3 text-sm font-black text-white hover:bg-bali-slate">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-6 lg:p-10">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>