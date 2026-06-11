@extends('layouts.public')

@section('content')
<section class="relative overflow-hidden bg-bali-navy py-20 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_16%_18%,rgba(13,148,136,0.35),transparent_30rem),radial-gradient(circle_at_84%_4%,rgba(249,115,22,0.30),transparent_28rem)]"></div>
    <div class="container-page relative grid gap-8 lg:grid-cols-[1fr_0.6fr] lg:items-end">
        <div>
            <span class="badge-teal bg-white/10 text-teal-100">Katalog Motor</span>
            <h1 class="mt-5 max-w-3xl text-5xl font-black leading-tight tracking-[-0.05em] md:text-6xl">Pilih unit yang siap untuk perjalanan aman di Bali.</h1>
            <p class="mt-5 max-w-2xl leading-8 text-slate-300">Filter motor berdasarkan tanggal, lokasi, dan jenis unit. Semua pengajuan tetap melewati persetujuan admin dan safety terms.</p>
        </div>
        <div class="glass-panel rounded-[1.8rem] p-6">
            <span class="text-xs font-black uppercase tracking-[0.18em] text-teal-200">Safety Included</span>
            <div class="mt-4 grid gap-3 text-sm font-bold text-white">
                <div class="rounded-2xl bg-white/10 p-4">Helm dan STNK dicek saat serah-terima</div>
                <div class="rounded-2xl bg-white/10 p-4">Terms wajib disetujui sebelum booking</div>
            </div>
        </div>
    </div>
</section>

<section class="relative z-10 -mt-10">
    <div class="container-page">
        <form action="{{ route('motorcycles.index') }}" method="GET" class="surface-card grid gap-4 rounded-[2rem] p-5 lg:grid-cols-[1fr_1fr_1fr_1fr_auto_auto] lg:items-end">
            <div><label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Lokasi</label><input type="text" name="delivery_location" value="{{ request('delivery_location') }}" placeholder="Area pengantaran" class="input-ui"></div>
            <div><label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Tanggal Mulai</label><input type="date" name="start_date" value="{{ request('start_date') }}" class="input-ui"></div>
            <div><label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Tanggal Selesai</label><input type="date" name="end_date" value="{{ request('end_date') }}" class="input-ui"></div>
            <div><label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Jenis Motor</label><select name="type" class="input-ui"><option value="">Semua Jenis</option>@foreach ($types as $type)<option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>@endforeach</select></div>
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('motorcycles.index') }}" class="btn-light">Reset</a>
        </form>
    </div>
</section>

<section class="py-16">
    <div class="container-page">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div><span class="badge-orange">Available Units</span><h2 class="mt-4 text-3xl font-black tracking-[-0.03em] text-bali-navy md:text-5xl">Motor siap diajukan</h2><p class="mt-3 text-bali-muted">Pilih unit, cek detail, lalu lanjutkan ke pengajuan sewa.</p></div>
            <div class="rounded-full bg-white px-5 py-3 text-sm font-black text-bali-navy shadow-sm">{{ $motorcycles->count() }} motor ditemukan</div>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($motorcycles as $motorcycle)
                <article class="motor-card group">
                    <div class="relative flex h-60 items-center justify-center bg-gradient-to-br from-slate-100 via-white to-orange-50 p-6 text-center font-black text-bali-navy">
                        <span class="absolute left-4 top-4 rounded-full bg-teal-50 px-3 py-1 text-xs font-black text-bali-teal-dark shadow-sm">Available</span>
                        <span class="absolute right-4 top-4 rounded-full bg-white px-3 py-1 text-xs font-black text-bali-navy shadow-sm">Safety Terms</span>
                        @if ($motorcycle->image)<img src="{{ asset('storage/' . $motorcycle->image) }}" alt="{{ $motorcycle->brand }} {{ $motorcycle->model }}" class="h-full w-full object-contain transition duration-200 group-hover:scale-105">@else {{ $motorcycle->brand }} {{ $motorcycle->model }} @endif
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-black text-bali-navy">{{ $motorcycle->brand }} {{ $motorcycle->model }}</h3>
                        <p class="mt-1 text-sm font-semibold text-bali-muted">{{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}</p>
                        <div class="mt-4 rounded-2xl bg-slate-100 p-4"><span class="block text-xs font-bold uppercase tracking-wide text-bali-muted">Plat Nomor</span><strong class="mt-1 block text-sm text-bali-navy">{{ $motorcycle->plate_number }}</strong></div>
                        <div class="mt-5 flex flex-wrap gap-2"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-muted">Helm</span><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-muted">STNK</span><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-muted">Checklist</span></div>
                        <div class="mt-5 flex items-center justify-between border-t border-bali-line pt-5"><div><strong class="text-lg font-black text-bali-navy">Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}</strong><small class="font-semibold text-bali-muted">/ hari</small></div><a href="{{ route('motorcycles.show', $motorcycle) }}" class="btn-primary px-5 py-2.5">Detail</a></div>
                    </div>
                </article>
            @empty
                <div class="col-span-full surface-card rounded-[2rem] p-12 text-center"><h3 class="text-2xl font-black text-bali-navy">Motor tidak ditemukan</h3><p class="mt-3 text-bali-muted">Coba ubah filter tanggal atau jenis motor.</p><a href="{{ route('motorcycles.index') }}" class="btn-dark mt-6">Reset Filter</a></div>
            @endforelse
        </div>
    </div>
</section>
@endsection
