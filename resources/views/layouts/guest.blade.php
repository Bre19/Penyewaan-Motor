<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'BaliMotor') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bali-soft text-bali-slate antialiased">
    <div class="grid min-h-screen lg:grid-cols-[0.92fr_1.08fr]">
        <section class="relative hidden overflow-hidden bg-bali-navy p-10 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(15,118,110,0.38),transparent_28rem),radial-gradient(circle_at_90%_20%,rgba(249,115,22,0.22),transparent_24rem)]"></div>

            <div class="relative">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-sm font-black text-bali-navy">
                        BM
                    </span>
                    <div>
                        <strong class="block text-2xl font-black">BaliMotor</strong>
                        <span class="text-xs font-bold text-slate-300">Motor Rental Bali</span>
                    </div>
                </a>
            </div>

            <div class="relative max-w-xl">
                <span class="badge-teal bg-white/10 text-teal-100">Digital Rental Flow</span>

                <h1 class="mt-6 text-5xl font-black leading-[0.98] tracking-[-0.06em]">
                    Sewa motor dengan proses yang lebih tertata.
                </h1>

                <p class="mt-6 text-base leading-8 text-slate-300">
                    Registrasi penyewa, verifikasi dokumen, pengajuan sewa, pembayaran,
                    dan status penyewaan dikelola dalam satu sistem.
                </p>

                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <strong class="block text-xl font-black">01</strong>
                        <span class="mt-1 block text-xs font-semibold text-slate-300">Register</span>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <strong class="block text-xl font-black">02</strong>
                        <span class="mt-1 block text-xs font-semibold text-slate-300">Booking</span>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4">
                        <strong class="block text-xl font-black">03</strong>
                        <span class="mt-1 block text-xs font-semibold text-slate-300">Verifikasi</span>
                    </div>
                </div>
            </div>

            <div class="relative text-sm font-semibold text-slate-400">
                © {{ date('Y') }} BaliMotor
            </div>
        </section>

        <main class="flex min-h-screen items-center justify-center px-5 py-10">
            <div class="w-full max-w-2xl">
                <div class="mb-8 text-center lg:hidden">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3">
                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-bali-navy text-sm font-black text-white">
                            BM
                        </span>
                        <div class="text-left leading-none">
                            <strong class="block text-2xl font-black text-bali-ink">BaliMotor</strong>
                            <span class="text-xs font-bold text-bali-muted">Motor Rental Bali</span>
                        </div>
                    </a>
                </div>

                <div class="surface-card rounded-[1.7rem] p-6 md:p-8">
                    @if (isset($slot))
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>