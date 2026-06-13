@extends('layouts.public')

@section('content')

<!-- HERO -->
<section class="relative overflow-hidden bg-bali-navy text-white">

    <!-- BACKGROUND LAYER -->
    <div class="absolute inset-0">
        <div class="absolute -top-32 -left-32 h-[420px] w-[420px] rounded-full bg-teal-500/20 blur-[120px]"></div>
        <div class="absolute top-0 right-0 h-[420px] w-[420px] rounded-full bg-orange-500/20 blur-[120px]"></div>
    </div>

    <div class="container-page relative grid min-h-[720px] items-center gap-12 py-20 lg:grid-cols-2">

        <!-- LEFT -->
        <div>
            <span class="badge-teal bg-white/10 text-teal-200">
                Motor Rental Bali
            </span>

            <h1 class="mt-6 text-5xl font-black leading-[0.95] tracking-[-0.05em] md:text-7xl">
                Sewa motor cepat, rapi, dan tanpa ribet.
            </h1>

            <p class="mt-6 max-w-xl text-lg text-slate-300">
                Semua proses dari booking sampai pembayaran tercatat jelas.
                Tidak ada lagi drama chat manual yang berantakan.
            </p>

            <div class="mt-10 flex gap-4">
                <a href="{{ route('motorcycles.index') }}" class="btn-primary">
                    Lihat Motor
                </a>
                <a href="#cara-sewa" class="btn-light">
                    Cara Sewa
                </a>
            </div>

            <!-- MINI STATS -->
            <div class="mt-12 grid grid-cols-3 gap-4 max-w-xl">
                <div class="rounded-xl bg-white/10 p-4 backdrop-blur">
                    <strong class="block text-xl font-black">Online</strong>
                    <span class="text-sm text-slate-300">Tracking jelas</span>
                </div>
                <div class="rounded-xl bg-white/10 p-4 backdrop-blur">
                    <strong class="block text-xl font-black">Admin</strong>
                    <span class="text-sm text-slate-300">Verifikasi</span>
                </div>
                <div class="rounded-xl bg-white/10 p-4 backdrop-blur">
                    <strong class="block text-xl font-black">Status</strong>
                    <span class="text-sm text-slate-300">Real-time</span>
                </div>
            </div>
        </div>


        <!-- RIGHT: BOOKING CARD -->
        <div class="relative">

            <!-- GLOW -->
            <div class="absolute inset-0 bg-gradient-to-br from-teal-500/20 to-orange-500/20 blur-2xl rounded-[2rem]"></div>

            <div class="relative surface-card rounded-[2rem] p-6 shadow-2xl">

                <h3 class="text-xl font-black text-bali-navy">
                    Booking Motor
                </h3>

                <form action="{{ route('motorcycles.index') }}" method="GET" class="mt-6 space-y-4">

                    <div>
                        <label class="text-xs font-bold text-bali-muted">Lokasi</label>
                        <input type="text" name="delivery_location" class="input-ui" placeholder="Kuta / Canggu / Ubud">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-bali-muted">Mulai</label>
                            <input type="date" name="start_date" class="input-ui">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-bali-muted">Selesai</label>
                            <input type="date" name="end_date" class="input-ui">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-bali-muted">Jenis Motor</label>
                        <select name="type" class="input-ui">
                            <option value="">Semua</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn-primary w-full">
                        Cari Motor
                    </button>

                </form>

            </div>

        </div>
    </div>
</section>


<!-- MOTOR LIST -->
<section class="py-24 bg-bali-soft">
    <div class="container-page">

        <div class="flex justify-between items-end mb-10">
            <div>
                <span class="badge-teal">Motor Tersedia</span>
                <h2 class="mt-3 text-4xl font-black text-bali-navy">
                    Pilihan motor untuk perjalananmu
                </h2>
            </div>

            <a href="{{ route('motorcycles.index') }}" class="btn-dark">
                Semua Motor
            </a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">

            @forelse ($motorcycles as $motorcycle)
                <article class="group rounded-2xl bg-white shadow-sm hover:shadow-xl transition overflow-hidden">

                    <div class="h-48 flex items-center justify-center bg-slate-50">
                        @if ($motorcycle->image)
                            <img src="{{ asset('storage/' . $motorcycle->image) }}"
                                 class="h-full object-contain group-hover:scale-105 transition">
                        @else
                            <span class="text-bali-muted">
                                {{ $motorcycle->brand }} {{ $motorcycle->model }}
                            </span>
                        @endif
                    </div>

                    <div class="p-5">

                        <h3 class="font-black text-bali-navy">
                            {{ $motorcycle->brand }} {{ $motorcycle->model }}
                        </h3>

                        <p class="text-sm text-bali-muted mt-1">
                            {{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}
                        </p>

                        <div class="mt-4 flex items-center justify-between">
                            <div>
                                <strong class="text-lg font-black text-bali-navy">
                                    Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}
                                </strong>
                                <span class="text-sm text-bali-muted">/ hari</span>
                            </div>

                            <a href="{{ route('motorcycles.show', $motorcycle) }}"
                               class="btn-dark px-4 py-2 text-sm">
                                Detail
                            </a>
                        </div>

                    </div>
                </article>

            @empty
                <p class="p-8 bg-white rounded-xl text-bali-muted">
                    Belum ada motor tersedia.
                </p>
            @endforelse

        </div>

    </div>
</section>


<!-- CARA SEWA -->
<section id="cara-sewa" class="py-24 bg-white">
    <div class="container-page">

        <div class="max-w-3xl mb-12">
            <span class="badge-orange">Cara Sewa</span>
            <h2 class="mt-3 text-4xl font-black text-bali-navy">
                Proses sewa dibuat jelas
            </h2>
        </div>

        @php
            $steps = [
                ['01', 'Register', 'Buat akun'],
                ['02', 'Pilih Motor', 'Tentukan tanggal'],
                ['03', 'Verifikasi', 'Admin cek data'],
                ['04', 'Bayar', 'Upload bukti'],
                ['05', 'Pengantaran', 'Motor dikirim'],
                ['06', 'Selesai', 'Transaksi selesai'],
            ];
        @endphp

        <div class="grid gap-6 md:grid-cols-3">

            @foreach ($steps as [$n, $t, $d])
                <div class="p-6 rounded-2xl border hover:shadow-lg transition">

                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-bali-navy text-white font-black">
                        {{ $n }}
                    </div>

                    <h3 class="mt-4 font-black text-bali-navy">
                        {{ $t }}
                    </h3>

                    <p class="text-sm text-bali-muted mt-2">
                        {{ $d }}
                    </p>

                </div>
            @endforeach

        </div>

    </div>
</section>


<!-- CTA -->
<section class="py-20 bg-bali-navy text-white text-center">
    <div class="container-page">

        <h2 class="text-4xl font-black">
            Siap sewa motor?
        </h2>

        <p class="mt-3 text-slate-300">
            Pilih motor dan ajukan sekarang.
        </p>

        <a href="{{ route('motorcycles.index') }}"
           class="btn-primary mt-6 inline-block">
            Mulai Sekarang
        </a>

    </div>
</section>

@endsection