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

            <div class="mt-8 flex flex-wrap gap-3">
                <span class="badge-green">Available</span>
                <span class="badge-gray">Helm</span>
                <span class="badge-gray">STNK</span>
                <span class="badge-gray">Delivery</span>
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

            <div class="grid gap-4 sm:grid-cols-3">
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
                    <span class="text-xs font-bold uppercase text-bali-muted">Plat</span>
                    <strong class="mt-2 block text-lg text-bali-ink">
                        {{ $motorcycle->plate_number }}
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

        </div>

        {{-- RIGHT SIDEBAR (ONLY CONVERSION AREA) --}}
        <div class="lg:sticky lg:top-24 lg:self-start">
            <div class="surface-card rounded-[2rem] p-8 space-y-6">

                <div>
                    <span class="badge-orange">Unit Ready</span>

                    <h2 class="mt-4 text-3xl font-black text-bali-navy">
                        {{ $motorcycle->brand }} {{ $motorcycle->model }}
                    </h2>

                    <p class="mt-2 text-sm font-semibold text-bali-muted">
                        {{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}
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
                    <a href="{{ route('bookings.create', $motorcycle) }}" class="btn-primary w-full">
                        Ajukan Penyewaan
                    </a>

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