@extends('layouts.public')

@section('content')
<section class="relative overflow-hidden bg-gradient-to-br from-bali-navy via-slate-900 to-blue-950 text-white">
    <div class="absolute left-0 top-0 h-96 w-96 rounded-full bg-bali-teal/20 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-bali-orange/20 blur-3xl"></div>

    <div class="relative mx-auto grid w-[min(1180px,92%)] items-center gap-12 py-20 lg:grid-cols-[1.05fr_0.95fr]">
        <div>
            <span class="mb-4 inline-flex rounded-full bg-white/10 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.18em] text-bali-teal">
                Motor Rental Bali
            </span>

            <h1 class="max-w-3xl text-5xl font-black leading-tight tracking-[-0.05em] md:text-6xl">
                Sewa Motor Mudah untuk Perjalanan Anda di Bali
            </h1>

            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                Pilih motor yang tersedia, tentukan durasi sewa, ajukan booking secara online,
                lalu tunggu persetujuan admin tanpa proses chat manual yang panjang.
            </p>

            <div class="mt-8 flex flex-wrap gap-4">
                <a href="{{ route('motorcycles.index') }}" class="rounded-full bg-bali-orange px-7 py-4 text-sm font-black text-white hover:bg-bali-orange-dark">
                    Lihat Motor
                </a>
                <a href="#cara-sewa" class="rounded-full bg-white px-7 py-4 text-sm font-black text-bali-navy hover:bg-slate-100">
                    Cara Sewa
                </a>
            </div>
        </div>

        <div class="relative min-h-[420px] rounded-[2rem] border border-white/10 bg-white/10 p-8 shadow-2xl backdrop-blur">
            <div class="absolute inset-8 rounded-[1.7rem] bg-gradient-to-br from-bali-teal via-bali-navy to-bali-orange"></div>

            <div class="absolute left-1/2 top-1/2 h-32 w-[70%] -translate-x-1/2 -translate-y-1/2 rounded-[6rem] bg-white shadow-2xl"></div>
            <div class="absolute left-1/2 top-[66%] h-4 w-[58%] -translate-x-1/2 rounded-full bg-black/30 blur-md"></div>

            <div class="absolute bottom-8 right-8 w-56 rounded-3xl bg-white p-5 text-bali-navy shadow-2xl">
                <span class="text-xs font-bold text-bali-muted">Available Today</span>
                <strong class="mt-2 block text-lg">Honda Vario 125</strong>
                <small class="mt-1 block text-bali-muted">Rp80.000 / hari</small>
            </div>
        </div>
    </div>

    <div class="relative mx-auto w-[min(1180px,92%)] pb-16">
        <form action="{{ route('motorcycles.index') }}" method="GET" class="grid gap-4 rounded-[1.7rem] bg-white p-5 text-bali-slate shadow-2xl lg:grid-cols-[1.2fr_1fr_1fr_1fr_auto]">
            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Lokasi Pengantaran</label>
                <input type="text" name="delivery_location" placeholder="Contoh: Kuta, Canggu, Ubud"
                    class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none focus:border-bali-teal">
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Tanggal Mulai</label>
                <input type="date" name="start_date"
                    class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none focus:border-bali-teal">
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Tanggal Selesai</label>
                <input type="date" name="end_date"
                    class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none focus:border-bali-teal">
            </div>

            <div>
                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Jenis Motor</label>
                <select name="type" class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none focus:border-bali-teal">
                    <option value="">Semua Jenis</option>
                    @foreach ($types as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="self-end rounded-2xl bg-bali-teal px-7 py-4 text-sm font-black text-white hover:bg-bali-teal-dark">
                Cari Motor
            </button>
        </form>
    </div>
</section>

<section class="py-20">
    <div class="mx-auto w-[min(1180px,92%)]">
        <div class="mb-10 max-w-3xl">
            <span class="mb-3 inline-flex text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
                Motor Tersedia
            </span>
            <h2 class="text-4xl font-black tracking-[-0.04em] text-bali-navy md:text-5xl">
                Pilih Motor Sesuai Kebutuhan Perjalanan
            </h2>
            <p class="mt-4 leading-8 text-bali-muted">
                Daftar motor yang siap digunakan untuk perjalanan harian maupun wisata di Bali.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($motorcycles as $motorcycle)
                <article class="overflow-hidden rounded-[1.7rem] border border-bali-line bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-2xl">
                    <div class="flex h-52 items-center justify-center bg-gradient-to-br from-cyan-50 to-slate-50 p-6 text-center font-black text-bali-navy">
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
                                <p class="mt-1 text-sm text-bali-muted">{{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}</p>
                            </div>
                            <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-black text-bali-teal-dark">Tersedia</span>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2">
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-muted">Helm</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-muted">STNK</span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-muted">Siap Antar</span>
                        </div>

                        <div class="mt-5 flex items-center justify-between border-t border-bali-line pt-5">
                            <div>
                                <strong class="text-lg font-black text-bali-navy">Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}</strong>
                                <small class="text-bali-muted">/ hari</small>
                            </div>
                            <a href="{{ route('motorcycles.show', $motorcycle) }}" class="rounded-full bg-bali-navy px-5 py-3 text-sm font-black text-white hover:bg-bali-slate">
                                Detail
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <p class="rounded-3xl bg-white p-8 text-bali-muted">Belum ada motor tersedia.</p>
            @endforelse
        </div>

        <div class="mt-10 flex justify-center">
            <a href="{{ route('motorcycles.index') }}" class="rounded-full border border-bali-line bg-white px-7 py-4 text-sm font-black text-bali-navy hover:bg-slate-50">
                Lihat Semua Motor
            </a>
        </div>
    </div>
</section>

<section id="cara-sewa" class="bg-slate-100 py-20">
    <div class="mx-auto w-[min(1180px,92%)]">
        <div class="mb-10 max-w-3xl">
            <span class="mb-3 inline-flex text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
                Cara Sewa
            </span>
            <h2 class="text-4xl font-black tracking-[-0.04em] text-bali-navy md:text-5xl">
                Proses Sewa Lebih Terstruktur
            </h2>
            <p class="mt-4 leading-8 text-bali-muted">
                Alur dibuat sesuai proses bisnis penyewaan motor dari registrasi sampai motor diterima penyewa.
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @php
                $steps = [
                    ['01', 'Register Akun', 'Penyewa membuat akun dan mengisi data diri dasar.'],
                    ['02', 'Lengkapi Dokumen', 'Upload paspor, visa, SIM jika ada, dan tanda tangan digital.'],
                    ['03', 'Pilih Motor', 'Pilih motor, tanggal sewa, durasi, dan lokasi pengantaran.'],
                    ['04', 'Menunggu Persetujuan', 'Admin memeriksa data penyewa, dokumen, dan ketersediaan motor.'],
                    ['05', 'Pembayaran', 'Pembayaran dapat dilakukan secara digital atau cash.'],
                    ['06', 'Motor Diantar', 'Setelah pembayaran valid, motor disiapkan dan diantar ke lokasi penyewa.'],
                ];
            @endphp

            @foreach ($steps as [$number, $title, $description])
                <div class="rounded-[1.7rem] border border-bali-line bg-white p-7 shadow-sm">
                    <span class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-bali-navy font-black text-white">
                        {{ $number }}
                    </span>
                    <h3 class="text-lg font-black text-bali-navy">{{ $title }}</h3>
                    <p class="mt-3 leading-7 text-bali-muted">{{ $description }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="keunggulan" class="py-20">
    <div class="mx-auto grid w-[min(1180px,92%)] items-center gap-12 lg:grid-cols-[1fr_0.85fr]">
        <div>
            <span class="mb-3 inline-flex text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
                Keunggulan
            </span>
            <h2 class="text-4xl font-black tracking-[-0.04em] text-bali-navy md:text-5xl">
                Digitalisasi Penyewaan Motor yang Lebih Efisien
            </h2>
            <p class="mt-5 leading-8 text-bali-muted">
                Website ini dirancang untuk menggantikan proses manual melalui chat menjadi proses pemesanan
                yang lebih rapi, tercatat, dan mudah dipantau.
            </p>

            <div class="mt-8 grid gap-5">
                <div class="border-l-4 border-bali-teal pl-5">
                    <strong class="text-bali-navy">Motor Tersedia dan Terdata</strong>
                    <p class="mt-2 text-bali-muted">Admin dapat mengelola data motor dan status ketersediaan.</p>
                </div>
                <div class="border-l-4 border-bali-teal pl-5">
                    <strong class="text-bali-navy">Dokumen Penyewa Tersimpan</strong>
                    <p class="mt-2 text-bali-muted">Paspor, visa, SIM, dan tanda tangan digital dapat dikelola melalui sistem.</p>
                </div>
                <div class="border-l-4 border-bali-teal pl-5">
                    <strong class="text-bali-navy">Status Penyewaan Jelas</strong>
                    <p class="mt-2 text-bali-muted">Penyewa dapat melihat status pengajuan, pembayaran, pengantaran, dan selesai.</p>
                </div>
            </div>
        </div>

        <div class="grid gap-5 rounded-[2rem] border border-bali-line bg-gradient-to-br from-teal-50 to-orange-50 p-7 shadow-2xl">
            <div class="rounded-3xl border border-bali-line bg-white p-6">
                <strong class="block text-bali-navy">Online Booking</strong>
                <span class="mt-2 block text-sm text-bali-muted">Pengajuan sewa tercatat otomatis.</span>
            </div>
            <div class="rounded-3xl border border-bali-line bg-white p-6">
                <strong class="block text-bali-navy">Cash / Digital</strong>
                <span class="mt-2 block text-sm text-bali-muted">Metode pembayaran lebih fleksibel.</span>
            </div>
            <div class="rounded-3xl border border-bali-line bg-white p-6">
                <strong class="block text-bali-navy">Delivery Fee</strong>
                <span class="mt-2 block text-sm text-bali-muted">Biaya pengantaran dapat dihitung dalam total sewa.</span>
            </div>
        </div>
    </div>
</section>

<section class="pb-20">
    <div class="mx-auto flex w-[min(1180px,92%)] flex-col gap-6 rounded-[2rem] bg-gradient-to-br from-bali-teal to-bali-navy p-10 text-white shadow-2xl md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-3xl font-black tracking-[-0.03em]">Siap Menjelajahi Bali dengan Motor Pilihan Anda?</h2>
            <p class="mt-3 text-slate-200">Lihat motor yang tersedia dan mulai ajukan penyewaan secara online.</p>
        </div>
        <a href="{{ route('motorcycles.index') }}" class="rounded-full bg-bali-orange px-7 py-4 text-center text-sm font-black text-white hover:bg-bali-orange-dark">
            Mulai Sewa
        </a>
    </div>
</section>
@endsection