@extends('layouts.public')

@section('content')

<section class="relative overflow-hidden bg-bali-navy text-white">
    <div class="absolute inset-0">
        <div class="absolute -top-28 -left-28 h-[420px] w-[420px] rounded-full bg-teal-500/20 blur-[120px]"></div>
        <div class="absolute -top-10 right-0 h-[380px] w-[380px] rounded-full bg-orange-500/20 blur-[120px]"></div>
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-bali-soft to-transparent"></div>
    </div>

    <div class="container-page relative py-20">
        <div class="max-w-3xl">
            <span class="badge-teal bg-white/10 text-teal-200">
                Detail Motor
            </span>

            <h1 class="mt-6 text-5xl font-black leading-[0.96] tracking-[-0.05em] md:text-6xl">
                {{ $motorcycle->brand }} {{ $motorcycle->model }}
            </h1>

            <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">
                Lihat unit, fasilitas, dan syarat sebelum mengajukan penyewaan.
            </p>

            @php
            $totalUnit = $motorcycle->stocks->count();

            $availableUnit = $motorcycle->stocks
                ->where('status', 'available')
                ->count();

            $bookedUnit = $motorcycle->stocks
                ->where('status', 'booked')
                ->count();

            $rentedUnit = $motorcycle->stocks
                ->where('status', 'rented')
                ->count();

            $maintenanceUnit = $motorcycle->stocks
                ->where('status', 'maintenance')
                ->count();

            @endphp

            <div class="mt-8 flex flex-wrap gap-3">

                <span class="badge-green">
                    {{ $availableUnit }} Unit Tersedia
                </span>

                <span class="badge-gray">
                    {{ $bookedUnit }} Booking
                </span>

                <span class="badge-gray">
                    {{ $rentedUnit }} Disewa
                </span>

                <span class="badge-gray">
                    {{ $maintenanceUnit }} Maintenance
                </span>

            </div>
        </div>
    </div>
</section>

<section class="-mt-12 pb-20">
    <div class="container-page grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">

        {{-- LEFT CONTENT --}}
        <div class="space-y-6">

            <div class="product-gallery p-6">
                <div class="flex min-h-[520px] items-center justify-center overflow-hidden rounded-[1.6rem] bg-slate-50">
                    @if ($motorcycle->image)
                        <img
                            src="{{ asset('storage/' . $motorcycle->image) }}"
                            alt="{{ $motorcycle->brand }} {{ $motorcycle->model }}"
                            class="h-full w-full object-contain p-8"
                        >
                    @else
                        <div class="text-center">
                            <div class="text-sm font-semibold text-bali-muted">
                                Gambar motor belum tersedia
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-4">
                <div class="feature-card">
                    <span class="text-xs font-bold uppercase text-bali-muted">Jenis</span>
                    <strong class="mt-2 block text-lg text-bali-ink">
                        {{ $motorcycle->type ?? '-' }}
                    </strong>
                </div>

                <div class="feature-card">
                    <span class="text-xs font-bold uppercase text-bali-muted">Tahun</span>
                    <strong class="mt-2 block text-lg text-bali-ink">
                        {{ $motorcycle->year ?? '-' }}
                    </strong>
                </div>

                <div class="feature-card">
                    <span class="text-xs font-bold uppercase text-bali-muted">
                        Total Unit
                    </span>

                    <strong class="mt-2 block text-lg text-bali-ink">

                        {{ $totalUnit }}

                    </strong>

                </div>

                <div class="feature-card">

                    <span class="text-xs font-bold uppercase text-bali-muted">
                        Unit Tersedia
                    </span>

                    <strong class="mt-2 block text-lg text-emerald-600">

                        {{ $availableUnit }}

                    </strong>
                </div>
            </div>

            <div class="surface-card rounded-[2rem] p-8">
                <h2 class="text-2xl font-black text-bali-ink">
                    Deskripsi Motor
                </h2>

                <p class="mt-4 leading-8 text-bali-muted">
                    {{ $motorcycle->description ?? 'Belum ada deskripsi untuk motor ini.' }}
                </p>
            </div>

            <div class="surface-card rounded-[2rem] p-8">
                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-2xl font-black text-bali-ink">
                            Daftar Unit Motor
                        </h2>

                        <p class="mt-2 text-sm text-bali-muted">
                            Setiap unit memiliki plat nomor, foto, dan status operasionalnya sendiri.
                        </p>

                    </div>

                    <div class="rounded-full bg-bali-soft px-5 py-2 text-sm font-bold text-bali-navy">

                        {{ $totalUnit }} Unit

                    </div>

                </div>

                <div class="mt-8 space-y-4">

                    @forelse($motorcycle->stocks as $stock)

                        @php

                            $badge = match($stock->status){

                                'available'
                                    => 'bg-emerald-100 text-emerald-700',

                                'booked'
                                    => 'bg-sky-100 text-sky-700',

                                'rented'
                                    => 'bg-orange-100 text-orange-700',

                                'maintenance'
                                    => 'bg-amber-100 text-amber-700',

                                default
                                    => 'bg-slate-100 text-slate-700'

                            };

                        @endphp

                        <div class="rounded-2xl border border-slate-100 p-5">

                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center">

                                <div class="h-24 w-32 overflow-hidden rounded-xl bg-slate-100 flex items-center justify-center">

                                    @if($stock->image)

                                        <img
                                            src="{{ asset('storage/'.$stock->image) }}"
                                            class="h-full w-full object-cover"
                                        >

                                    @else

                                        <div class="text-xs text-slate-400 font-semibold">

                                            No Image

                                        </div>

                                    @endif

                                </div>

                                <div class="flex-1">

                                    <div class="font-black text-lg text-bali-ink">

                                        {{ $stock->stock_code }}

                                    </div>

                                    <div class="mt-1 text-sm text-slate-500">

                                        Plat Nomor :
                                        <strong>
                                            {{ $stock->plate_number }}
                                        </strong>

                                    </div>

                                    @if($stock->notes)

                                        <div class="mt-2 text-sm text-slate-500">

                                            {{ $stock->notes }}

                                        </div>

                                    @endif

                                </div>

                                <div>

                                    <span class="rounded-full px-4 py-2 text-sm font-bold {{ $badge }}">

                                        {{ $stock->statusLabel() }}

                                    </span>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="rounded-2xl bg-bali-soft p-8 text-center">

                            <div class="font-semibold text-bali-muted">

                                Belum ada unit motor.

                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

        {{-- RIGHT SIDEBAR (ONLY CONVERSION AREA) --}}
        <div class="lg:sticky lg:top-24 lg:self-start">
            <div class="surface-card rounded-[2rem] p-8 space-y-6">

                <div>

                    @if($availableUnit > 0)

                        <span class="badge-green">
                            {{ $availableUnit }} Unit Siap Disewa
                        </span>

                    @else

                        <span class="badge-orange">
                            Stok Sedang Habis
                        </span>

                    @endif

                    <h2 class="mt-4 text-3xl font-black text-bali-navy">

                        {{ $motorcycle->brand }}
                        {{ $motorcycle->model }}

                    </h2>

                    <p class="mt-2 text-sm font-semibold text-bali-muted">

                        {{ $motorcycle->type ?? '-' }}
                        •

                        {{ $motorcycle->year ?? '-' }}

                    </p>
                </div>

                {{-- SINGLE PRICE BLOCK --}}
                <div class="rounded-[1.6rem] bg-bali-navy p-6 text-white">
                    <span class="text-sm text-slate-300">Harga per hari</span>
                    <strong class="block text-4xl font-black mt-2">
                        Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}
                    </strong>
                    <p class="text-sm mt-2 text-slate-300">
                        Total menyesuaikan durasi sewa.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3">

                    <div class="rounded-2xl bg-emerald-50 p-4">

                        <div class="text-xs font-bold uppercase text-emerald-600">

                            Unit Tersedia

                        </div>

                        <div class="mt-2 text-3xl font-black text-emerald-700">

                            {{ $availableUnit }}

                        </div>

                    </div>

                    <div class="rounded-2xl bg-slate-100 p-4">

                        <div class="text-xs font-bold uppercase text-slate-500">

                            Total Unit

                        </div>

                        <div class="mt-2 text-3xl font-black text-bali-navy">

                            {{ $totalUnit }}

                        </div>

                    </div>

                </div>

                <div class="grid gap-3">
                    <div class="rounded-2xl bg-bali-soft p-4">
                        <strong class="text-sm text-bali-ink">Termasuk</strong>
                        <div class="mt-2 flex gap-2 flex-wrap">
                            <span class="badge-gray">Helm</span>
                            <span class="badge-gray">STNK</span>
                            <span class="badge-gray">Checklist</span>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-bali-soft p-4">
                        <strong class="text-sm text-bali-ink">Safety Requirement</strong>
                        <div class="mt-2 text-sm text-bali-muted space-y-1">
                            <div>Wajib memakai helm</div>
                            <div>Setujui syarat sebelum booking</div>
                            <div>Unit dicek sebelum serah-terima</div>
                        </div>
                    </div>
                </div>

                {{-- ONLY CTA --}}
                <div class="space-y-3 pt-2">
                    @if($availableUnit > 0)

                        <a
                            href="{{ route('bookings.create', $motorcycle) }}"
                            class="btn-primary w-full"
                        >
                            Ajukan Penyewaan
                        </a>

                    @else

                        <button
                            disabled
                            class="btn-light w-full cursor-not-allowed opacity-60"
                        >
                            Seluruh Unit Sedang Digunakan
                        </button>

                    @endif

                    <a href="{{ route('motorcycles.index') }}" class="btn-light w-full">
                        Kembali ke Katalog
                    </a>
                </div>

                <p class="text-xs text-center text-bali-muted">
                    Pengajuan diverifikasi admin sebelum pembayaran.
                </p>

            </div>
        </div>

    </div>
</section>

@endsection