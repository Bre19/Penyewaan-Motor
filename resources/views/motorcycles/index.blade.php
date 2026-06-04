@extends('layouts.public')

@section('content')
<section class="bg-gradient-to-br from-bali-navy via-slate-900 to-blue-950 py-20 text-white">
    <div class="mx-auto w-[min(1180px,92%)]">
        <span class="mb-3 inline-flex text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
            Katalog Motor
        </span>
        <h1 class="text-5xl font-black tracking-[-0.05em]">Daftar Motor Tersedia</h1>
        <p class="mt-4 max-w-2xl leading-8 text-slate-300">
            Pilih motor sesuai kebutuhan perjalanan Anda di Bali.
        </p>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto w-[min(1180px,92%)]">
        <form action="{{ route('motorcycles.index') }}" method="GET" class="mb-8 flex flex-col gap-4 rounded-[1.7rem] border border-bali-line bg-white p-5 shadow-sm md:flex-row md:items-end">
            <div class="w-full md:max-w-xs">
                <label class="mb-2 block text-xs font-black uppercase tracking-wide text-bali-navy">Jenis Motor</label>
                <select name="type" class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none focus:border-bali-teal">
                    <option value="">Semua Jenis</option>
                    @foreach ($types as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="rounded-2xl bg-bali-teal px-7 py-4 text-sm font-black text-white hover:bg-bali-teal-dark">
                Filter
            </button>

            @if (request()->hasAny(['type', 'start_date', 'end_date', 'delivery_location']))
                <a href="{{ route('motorcycles.index') }}" class="rounded-2xl bg-slate-100 px-7 py-4 text-center text-sm font-black text-bali-navy hover:bg-slate-200">
                    Reset
                </a>
            @endif
        </form>

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
                            <span class="rounded-full bg-teal-50 px-3 py-1 text-xs font-black text-bali-teal-dark">
                                Tersedia
                            </span>
                        </div>

                        <p class="mt-4 text-sm text-bali-muted">Plat: {{ $motorcycle->plate_number }}</p>

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
                <div class="col-span-full rounded-[1.7rem] border border-bali-line bg-white p-10 text-center">
                    <h3 class="text-xl font-black text-bali-navy">Motor tidak ditemukan</h3>
                    <p class="mt-2 text-bali-muted">Coba ubah filter atau cek kembali daftar motor tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection