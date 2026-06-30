<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Admin Panel - BaliMotor' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-shell text-bali-slate antialiased">
    @php
        $adminLinks = [
            [
                'label' => 'Dashboard',
                'route' => route('admin.dashboard'),
                'active' => request()->routeIs('admin.dashboard'),
            ],
            [
                'label' => 'Motor',
                'route' => route('admin.motorcycles.index'),
                'active' => request()->routeIs('admin.motorcycles.*'),
            ],
            [
                'label' => 'Booking',
                'route' => route('admin.bookings.index'),
                'active' => request()->routeIs('admin.bookings.*'),
            ],
            [
                'label' => 'Pembayaran',
                'route' => route('admin.payments.index'),
                'active' => request()->routeIs('admin.payments.*'),
            ],
            [
                'label' => 'Katalog Website',
                'route' => route('motorcycles.index'),
                'active' => false,
            ],
        ];
    @endphp

    <div class="min-h-screen lg:grid lg:grid-cols-[292px_1fr]">
        <aside class="admin-sidebar hidden lg:block">
            <div class="sticky top-0 flex min-h-screen flex-col p-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-sm font-black text-bali-navy shadow-xl">
                        BM
                    </span>

                    <div>
                        <strong class="block text-lg leading-none">BaliMotor</strong>
                        <span class="mt-1 block text-xs font-bold text-slate-400">Admin Center</span>
                    </div>
                </a>

                <div class="mt-8 rounded-[1.5rem] border border-white/10 bg-white/10 p-5">
                    <span class="text-xs font-black uppercase tracking-[0.18em] text-teal-200">
                        Operational Suite
                    </span>
                    <strong class="mt-2 block text-lg">Rental Control</strong>
                    <p class="mt-2 text-xs leading-6 text-slate-400">
                        Kelola booking, pembayaran, pengantaran, dan evaluasi penyewa.
                    </p>
                </div>

                <nav class="mt-8 grid gap-2">
                    @foreach ($adminLinks as $link)
                        <a
                            href="{{ $link['route'] }}"
                            class="admin-nav-link {{ $link['active'] ? 'admin-nav-link-active' : 'admin-nav-link-idle' }}"
                        >
                            <span class="status-dot"></span>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="mt-auto rounded-[1.5rem] border border-white/10 bg-white/10 p-5">
                    <span class="block text-xs font-black uppercase tracking-wide text-slate-400">
                        Login sebagai
                    </span>
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
            <header class="admin-topbar">
                <div class="flex min-h-20 items-center justify-between gap-4 px-5 py-4 lg:px-10">
                    <div>
                        <span class="text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
                            Admin BaliMotor
                        </span>
                        <h1 class="mt-1 text-xl font-black text-bali-ink">
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>

                    <div class="hidden items-center gap-3 sm:flex">
                        <a href="{{ route('home') }}" class="btn-light px-5 py-3">
                            Website
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="lg:hidden">
                            @csrf
                            <button type="submit" class="btn-dark px-5 py-3">
                                Logout
                            </button>
                        </form>
                    </div>

                    <details class="relative lg:hidden">
                        <summary class="flex h-11 w-11 cursor-pointer list-none items-center justify-center rounded-2xl border border-bali-line bg-white text-lg font-black text-bali-navy shadow-clean">
                            ☰
                        </summary>

                        <div class="absolute right-0 mt-3 w-72 rounded-[1.5rem] border border-bali-line bg-white p-4 shadow-2xl">
                            <div class="mb-4 rounded-2xl bg-bali-soft p-4">
                                <strong class="block text-sm text-bali-ink">{{ auth()->user()->name }}</strong>
                                <span class="mt-1 block truncate text-xs font-semibold text-bali-muted">
                                    {{ auth()->user()->email }}
                                </span>
                            </div>

                            <nav class="grid gap-2">
                                @foreach ($adminLinks as $link)
                                    <a
                                        href="{{ $link['route'] }}"
                                        class="rounded-2xl px-4 py-3 text-sm font-black {{ $link['active'] ? 'bg-bali-navy text-white' : 'text-bali-muted hover:bg-slate-100 hover:text-bali-navy' }}"
                                    >
                                        {{ $link['label'] }}
                                    </a>
                                @endforeach
                            </nav>

                            <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-bali-line pt-4">
                                @csrf
                                <button type="submit" class="btn-dark w-full">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </details>
                </div>
            </header>

            <div class="p-5 lg:p-10">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>