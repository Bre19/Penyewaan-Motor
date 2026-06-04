@extends('layouts.public')

@section('content')
<section class="relative overflow-hidden bg-bali-navy text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_15%,rgba(13,148,136,0.35),transparent_30rem),radial-gradient(circle_at_85%_20%,rgba(249,115,22,0.28),transparent_28rem)]"></div>
    <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-bali-soft to-transparent"></div>

    <div class="container-page relative grid min-h-[720px] items-center gap-12 py-20 lg:grid-cols-[1.02fr_0.98fr]">
        <div>
            <span class="badge-teal bg-white/10 text-teal-200">
                Motor Rental Bali
            </span>

            <h1 class="mt-6 max-w-4xl text-5xl font-black leading-[0.95] tracking-[-0.06em] md:text-7xl">
                Sewa motor lebih rapi, cepat, dan terpantau.
            </h1>

            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                Pilih motor, ajukan sewa, upload pembayaran, dan pantau status penyewaan dari satu sistem.
                Tidak perlu lagi tenggelam dalam chat manual yang nasibnya mirip laci penuh struk.
            </p>

            <div class="mt-9 flex flex-wrap gap-4">
                <a href="{{ route('motorcycles.index') }}" class="btn-primary">
                    Lihat Katalog Motor
                </a>
                <a href="#cara-sewa" class="btn-light">
                    Lihat Cara Sewa
                </a>
            </div>

            <div class="mt-10 grid max-w-2xl gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                    <strong class="block text-2xl font-black">Online</strong>
                    <span class="mt-1 block text-sm text-slate-300">Booking tercatat</span>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                    <strong class="block text-2xl font-black">Admin</strong>
                    <span class="mt-1 block text-sm text-slate-300">Verifikasi jelas</span>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                    <strong class="block text-2xl font-black">Status</strong>
                    <span class="mt-1 block text-sm text-slate-300">Mudah dipantau</span>
                </div>
            </div>
        </div>

        <div class="relative">
            <div class="glass-panel rounded-[2.25rem] p-6">
                <div class="relative min-h-[460px] overflow-hidden rounded-[1.8rem] bg-gradient-to-br from-slate-950 via-slate-800 to-teal-900 p-7">
                    <div class="absolute -right-16 -top-16 h-52 w-52 rounded-full bg-bali-orange/30 blur-3xl"></div>
                    <div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-bali-teal/30 blur-3xl"></div>

                    <div class="relative flex items-center justify-between">
                        <div>
                            <span class="text-xs font-black uppercase tracking-[0.18em] text-teal-200">Available Today</span>
                            <h3 class="mt-2 text-2xl font-black">Honda Vario 125</h3>
                        </div>
                        <span class="rounded-full bg-white px-4 py-2 text-xs font-black text-bali-navy">Ready</span>
                    </div>

                    <div class="relative mt-16 flex h-48 items-center justify-center">
                        <div class="absolute h-24 w-[82%] rounded-full bg-white shadow-2xl"></div>
                        <div class="absolute bottom-8 h-5 w-[68%] rounded-full bg-black/35 blur-lg"></div>
                        <div class="absolute left-10 top-28 h-20 w-20 rounded-full border-[14px] border-slate-950 bg-white"></div>
                        <div class="absolute right-12 top-28 h-20 w-20 rounded-full border-[14px] border-slate-950 bg-white"></div>
                    </div>

                    <div class="relative mt-12 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-white p-5 text-bali-navy">
                            <span class="text-xs font-bold text-bali-muted">Harga</span>
                            <strong class="mt-1 block text-xl font-black">Rp80.000 / hari</strong>
                        </div>
                        <div class="rounded-2xl bg-white/10 p-5">
                            <span class="text-xs font-bold text-slate-300">Fasilitas</span>
                            <strong class="mt-1 block text-xl font-black">Helm + STNK</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute -bottom-8 -left-6 hidden rounded-[1.5rem] bg-white p-5 text-bali-navy shadow-2xl md:block">
                <span class="text-xs font-black uppercase tracking-wide text-bali-teal">Booking Flow</span>
                <strong class="mt-2 block">Ajukan → Verifikasi → Bayar</strong>
            </div>
        </div>
    </div>
</section>

<section class="-mt-16 relative z-10">
    <div class="container-page">
        <form action="{{ route('motorcycles.index') }}" method="GET" class="surface-card grid gap-4 rounded-[2rem] p-5 lg:grid-cols-[1.2fr_1fr_1fr_1fr_auto]">
            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Lokasi Pengantaran</label>
                <input type="text" name="delivery_location" placeholder="Kuta, Canggu, Ubud" class="input-ui">
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Tanggal Mulai</label>
                <input type="date" name="start_date" class="input-ui">
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Tanggal Selesai</label>
                <input type="date" name="end_date" class="input-ui">
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Jenis Motor</label>
                <select name="type" class="input-ui">
                    <option value="">Semua Jenis</option>
                    @foreach ($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-primary self-end">
                Cari Motor
            </button>
        </form>
    </div>
</section>

<section class="py-24">
    <div class="container-page">
        <div class="mb-10 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div class="max-w-3xl">
                <span class="badge-teal">Motor Tersedia</span>
                <h2 class="mt-4 text-4xl font-black tracking-[-0.04em] text-bali-navy md:text-5xl">
                    Pilihan motor untuk perjalanan di Bali.
                </h2>
                <p class="mt-4 leading-8 text-bali-muted">
                    Motor siap digunakan untuk kebutuhan wisata, harian, maupun perjalanan singkat.
                </p>
            </div>

            <a href="{{ route('motorcycles.index') }}" class="btn-dark">
                Semua Motor
            </a>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($motorcycles as $motorcycle)
                <article class="motor-card">
                    <div class="flex h-56 items-center justify-center bg-gradient-to-br from-slate-100 via-white to-orange-50 p-6 text-center font-black text-bali-navy">
                        @if ($motorcycle->image)
                            <img src="{{ asset('storage/' . $motorcycle->image) }}" alt="{{ $motorcycle->brand }} {{ $motorcycle->model }}" class="h-full w-full object-contain">
                        @else
                            {{ $motorcycle->brand }} {{ $motorcycle->model }}
                        @endif
                    </div>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="font-black text-bali-navy">{{ $motorcycle->brand }} {{ $motorcycle->model }}</h3>
                                <p class="mt-1 text-sm font-semibold text-bali-muted">{{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}</p>
                            </div>
                            <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-black text-bali-teal-dark">Ready</span>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-muted">Helm</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-muted">STNK</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-muted">Siap Antar</span>
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-bali-line pt-5">
                            <div>
                                <strong class="text-lg font-black text-bali-navy">Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}</strong>
                                <small class="font-semibold text-bali-muted">/ hari</small>
                            </div>
                            <a href="{{ route('motorcycles.show', $motorcycle) }}" class="btn-dark px-5 py-2.5">
                                Detail
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="rounded-3xl bg-white p-8 text-bali-muted">Belum ada motor tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

<section id="cara-sewa" class="bg-white py-24">
    <div class="container-page">
        <div class="mb-10 max-w-3xl">
            <span class="badge-orange">Cara Sewa</span>
            <h2 class="mt-4 text-4xl font-black tracking-[-0.04em] text-bali-navy md:text-5xl">
                Alur sewa dibuat lebih jelas.
            </h2>
            <p class="mt-4 leading-8 text-bali-muted">
                Dari registrasi sampai motor diterima, semua proses tercatat dan dapat dipantau.
            </p>
        </div>

        @php
            $steps = [
                ['01', 'Register Akun', 'Penyewa membuat akun dan mengisi data awal.'],
                ['02', 'Pilih Motor', 'Pilih motor, tanggal sewa, durasi, dan lokasi pengantaran.'],
                ['03', 'Verifikasi Admin', 'Admin memeriksa data penyewa dan ketersediaan motor.'],
                ['04', 'Pembayaran', 'Penyewa upload bukti pembayaran untuk diverifikasi.'],
                ['05', 'Motor Diantar', 'Motor disiapkan dan diantar ke lokasi penyewa.'],
                ['06', 'Selesai', 'Motor dikembalikan dan transaksi diselesaikan.'],
            ];
        @endphp

        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($steps as [$number, $title, $description])
                <div class="soft-card rounded-[1.7rem] p-7">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-bali-navy font-black text-white">
                        {{ $number }}
                    </span>
                    <h3 class="mt-6 text-lg font-black text-bali-navy">{{ $title }}</h3>
                    <p class="mt-3 leading-7 text-bali-muted">{{ $description }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="keunggulan" class="py-24">
    <div class="container-page grid items-center gap-12 lg:grid-cols-[1fr_0.9fr]">
        <div>
            <span class="badge-teal">Keunggulan</span>
            <h2 class="mt-4 text-4xl font-black tracking-[-0.04em] text-bali-navy md:text-5xl">
                Website rental yang fokus ke proses, bukan sekadar pajangan.
            </h2>
            <p class="mt-5 leading-8 text-bali-muted">
                Sistem membantu admin dan penyewa mengurangi proses manual, memperjelas status,
                dan menyimpan data transaksi secara lebih tertata.
            </p>

            <div class="mt-8 grid gap-5">
                <div class="rounded-2xl border-l-4 border-bali-teal bg-white p-5 shadow-sm">
                    <strong class="text-bali-navy">Motor Tersedia dan Terdata</strong>
                    <p class="mt-2 text-bali-muted">Admin dapat mengelola data motor dan memantau status penyewaan.</p>
                </div>
                <div class="rounded-2xl border-l-4 border-bali-orange bg-white p-5 shadow-sm">
                    <strong class="text-bali-navy">Pembayaran Diverifikasi</strong>
                    <p class="mt-2 text-bali-muted">Bukti pembayaran dikirim oleh penyewa dan dikonfirmasi oleh admin.</p>
                </div>
                <div class="rounded-2xl border-l-4 border-bali-teal bg-white p-5 shadow-sm">
                    <strong class="text-bali-navy">Status Penyewaan Jelas</strong>
                    <p class="mt-2 text-bali-muted">Setiap booking memiliki status yang dapat dipantau dari dashboard.</p>
                </div>
            </div>
        </div>

        <div class="surface-card rounded-[2rem] p-7">
            <div class="grid gap-5">
                <div class="rounded-3xl bg-bali-navy p-6 text-white">
                    <span class="text-sm font-bold text-slate-300">Operational Flow</span>
                    <strong class="mt-2 block text-2xl font-black">Booking → Payment → Delivery</strong>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="rounded-3xl bg-slate-100 p-6">
                        <strong class="block text-bali-navy">User Dashboard</strong>
                        <span class="mt-2 block text-sm text-bali-muted">Pantau booking dan pembayaran.</span>
                    </div>
                    <div class="rounded-3xl bg-orange-50 p-6">
                        <strong class="block text-bali-navy">Admin Panel</strong>
                        <span class="mt-2 block text-sm text-bali-muted">Verifikasi booking dan pembayaran.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection