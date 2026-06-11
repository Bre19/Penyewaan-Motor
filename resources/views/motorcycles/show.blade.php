@extends('layouts.public')

@section('content')
<section class="relative overflow-hidden bg-bali-navy py-20 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(13,148,136,0.34),transparent_30rem),radial-gradient(circle_at_85%_5%,rgba(249,115,22,0.28),transparent_26rem)]"></div>

    <div class="container-page relative">
        <span class="badge-teal bg-white/10 text-teal-200">
            Detail Motor
        </span>

        <div class="mt-5 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="max-w-4xl text-5xl font-black leading-tight tracking-[-0.05em] md:text-6xl">
                    {{ $motorcycle->brand }} {{ $motorcycle->model }}
                </h1>
                <p class="mt-5 max-w-2xl leading-8 text-slate-300">
                    Periksa detail unit, harga sewa, dan informasi utama sebelum mengajukan booking.
                </p>
            </div>

            <div class="glass-panel rounded-[1.5rem] p-5">
                <span class="block text-xs font-black uppercase tracking-[0.18em] text-teal-200">Harga Sewa</span>
                <strong class="mt-2 block text-3xl font-black">
                    Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}
                    <span class="text-base text-slate-300">/ hari</span>
                </strong>
            </div>
        </div>
    </div>
</section>

<section class="py-16">
    <div class="container-page grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
        <div class="surface-card overflow-hidden rounded-[2rem] p-6">
            <div class="relative flex min-h-[520px] items-center justify-center overflow-hidden rounded-[1.7rem] bg-gradient-to-br from-slate-100 via-white to-orange-50 p-8 text-center text-3xl font-black text-bali-navy">
                <span class="absolute left-5 top-5 rounded-full bg-white px-4 py-2 text-xs font-black text-bali-teal-dark shadow-sm">
                    Tersedia
                </span>

                @if ($motorcycle->image)
                    <img src="{{ asset('storage/' . $motorcycle->image) }}" alt="{{ $motorcycle->brand }} {{ $motorcycle->model }}" class="h-full w-full object-contain">
                @else
                    {{ $motorcycle->brand }} {{ $motorcycle->model }}
                @endif
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl bg-slate-100 p-5">
                    <span class="text-xs font-black uppercase tracking-wide text-bali-muted">Jenis</span>
                    <strong class="mt-2 block text-bali-navy">{{ $motorcycle->type ?? '-' }}</strong>
                </div>

                <div class="rounded-2xl bg-slate-100 p-5">
                    <span class="text-xs font-black uppercase tracking-wide text-bali-muted">Tahun</span>
                    <strong class="mt-2 block text-bali-navy">{{ $motorcycle->year ?? '-' }}</strong>
                </div>

                <div class="rounded-2xl bg-slate-100 p-5">
                    <span class="text-xs font-black uppercase tracking-wide text-bali-muted">Plat</span>
                    <strong class="mt-2 block text-bali-navy">{{ $motorcycle->plate_number }}</strong>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="surface-card rounded-[2rem] p-8">
                <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                    <div>
                        <span class="badge-orange">Unit Ready</span>
                        <h2 class="mt-4 text-4xl font-black tracking-[-0.04em] text-bali-navy">
                            {{ $motorcycle->brand }} {{ $motorcycle->model }}
                        </h2>
                        <p class="mt-2 font-semibold text-bali-muted">
                            {{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}
                        </p>
                    </div>

                    <span class="w-fit rounded-full bg-teal-50 px-4 py-2 text-xs font-black text-bali-teal-dark">
                        Siap Disewa
                    </span>
                </div>

                <div class="mt-8 rounded-[1.7rem] bg-bali-navy p-6 text-white">
                    <span class="block text-sm font-bold text-slate-300">Harga per hari</span>
                    <strong class="mt-2 block text-4xl font-black">
                        Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}
                    </strong>
                    <p class="mt-2 text-sm leading-6 text-slate-300">
                        Total biaya akan dihitung otomatis berdasarkan durasi sewa.
                    </p>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-black text-bali-navy">Deskripsi</h3>
                    <p class="mt-3 leading-8 text-bali-muted">
                        {{ $motorcycle->description ?? 'Belum ada deskripsi untuk motor ini.' }}
                    </p>
                </div>

                <div class="mt-8 flex flex-wrap gap-2">
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-bold text-bali-muted">Helm</span>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-bold text-bali-muted">STNK</span>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-bold text-bali-muted">Siap Antar</span>
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-bold text-bali-muted">Verifikasi Admin</span>
                </div>
            </div>

            <div class="surface-card rounded-[2rem] p-8">
                <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-bali-navy">Ajukan Penyewaan</h3>
                        <p class="mt-2 max-w-xl text-sm leading-7 text-bali-muted">
                            Pengajuan akan masuk ke admin untuk pengecekan data penyewa, tanggal sewa, dan ketersediaan unit.
                        </p>
                    </div>

                    <a href="{{ route('bookings.create', $motorcycle) }}" class="btn-primary">
                        Ajukan Sewa
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection