@extends('layouts.public')

@section('content')
<section class="bg-gradient-to-br from-bali-navy via-slate-900 to-blue-950 py-20 text-white">
    <div class="mx-auto w-[min(1180px,92%)]">
        <span class="mb-3 inline-flex text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
            Detail Motor
        </span>
        <h1 class="text-5xl font-black tracking-[-0.05em]">{{ $motorcycle->brand }} {{ $motorcycle->model }}</h1>
        <p class="mt-4 max-w-2xl leading-8 text-slate-300">
            Lihat detail motor sebelum mengajukan penyewaan.
        </p>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto grid w-[min(1180px,92%)] gap-8 lg:grid-cols-[0.95fr_1.05fr]">
        <div class="flex min-h-[520px] items-center justify-center rounded-[2rem] border border-bali-line bg-gradient-to-br from-cyan-50 to-slate-50 p-8 text-center text-3xl font-black text-bali-navy shadow-2xl">
            @if ($motorcycle->image)
                <img src="{{ asset('storage/' . $motorcycle->image) }}" alt="{{ $motorcycle->brand }} {{ $motorcycle->model }}" class="h-full w-full object-contain">
            @else
                {{ $motorcycle->brand }} {{ $motorcycle->model }}
            @endif
        </div>

        <div class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-2xl">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <h2 class="text-4xl font-black tracking-[-0.04em] text-bali-navy">
                        {{ $motorcycle->brand }} {{ $motorcycle->model }}
                    </h2>
                    <p class="mt-2 text-bali-muted">{{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}</p>
                </div>

                <span class="rounded-full bg-teal-50 px-4 py-2 text-xs font-black text-bali-teal-dark">
                    Tersedia
                </span>
            </div>

            <div class="mt-8 rounded-3xl bg-slate-100 p-6">
                <strong class="text-3xl font-black text-bali-navy">
                    Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}
                </strong>
                <span class="text-bali-muted">/ hari</span>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-bali-line p-5">
                    <span class="block text-sm text-bali-muted">Merek</span>
                    <strong class="mt-1 block text-bali-navy">{{ $motorcycle->brand }}</strong>
                </div>
                <div class="rounded-2xl border border-bali-line p-5">
                    <span class="block text-sm text-bali-muted">Model</span>
                    <strong class="mt-1 block text-bali-navy">{{ $motorcycle->model }}</strong>
                </div>
                <div class="rounded-2xl border border-bali-line p-5">
                    <span class="block text-sm text-bali-muted">Jenis</span>
                    <strong class="mt-1 block text-bali-navy">{{ $motorcycle->type ?? '-' }}</strong>
                </div>
                <div class="rounded-2xl border border-bali-line p-5">
                    <span class="block text-sm text-bali-muted">Tahun</span>
                    <strong class="mt-1 block text-bali-navy">{{ $motorcycle->year ?? '-' }}</strong>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-xl font-black text-bali-navy">Deskripsi</h3>
                <p class="mt-3 leading-8 text-bali-muted">
                    {{ $motorcycle->description ?? 'Belum ada deskripsi untuk motor ini.' }}
                </p>
            </div>

            <div class="mt-8 border-t border-bali-line pt-8">
                <h3 class="text-xl font-black text-bali-navy">Ajukan Penyewaan</h3>
                <p class="mt-3 leading-8 text-bali-muted">
                    Fitur booking akan diaktifkan setelah modul login, register penyewa,
                    dan upload dokumen selesai dibuat.
                </p>

                <div class="mt-5 rounded-2xl border border-teal-200 bg-teal-50 p-5">
                    <span class="block text-sm text-bali-muted">Status awal pengajuan</span>
                    <strong class="mt-1 block text-bali-teal-dark">Menunggu Persetujuan Admin</strong>
                </div>

                <a href="#" class="mt-6 inline-flex rounded-full bg-bali-orange px-7 py-4 text-sm font-black text-white opacity-60">
                    Ajukan Sewa
                </a>
            </div>
        </div>
    </div>
</section>
@endsection