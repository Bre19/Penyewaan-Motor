@extends('layouts.public')

@section('content')

<!-- HERO -->
<section class="relative overflow-hidden bg-bali-navy py-20 text-white">
    <div class="absolute inset-0">
        <div class="absolute -top-32 -left-32 h-[420px] w-[420px] rounded-full bg-teal-500/20 blur-[120px]"></div>
        <div class="absolute top-0 right-0 h-[420px] w-[420px] rounded-full bg-orange-500/20 blur-[120px]"></div>
    </div>

    <div class="container-page relative">
        <span class="badge-teal bg-white/10 text-teal-100">Katalog Motor</span>

        <h1 class="mt-5 max-w-3xl text-5xl font-black tracking-[-0.05em] md:text-6xl">
            Temukan motor yang siap digunakan.
        </h1>

        <p class="mt-5 max-w-2xl text-slate-300">
            Filter berdasarkan lokasi, tanggal, dan jenis motor.
            Semua booking tetap melalui verifikasi admin.
        </p>
    </div>
</section>


<!-- CONTENT -->
<section class="py-20 bg-bali-soft">
    <div class="container-page grid gap-10 lg:grid-cols-[300px_1fr]">

        <!-- SIDEBAR FILTER -->
        <aside class="h-fit sticky top-24">

            <form action="{{ route('motorcycles.index') }}" method="GET"
                  class="surface-card rounded-2xl p-6 space-y-5">

                <h3 class="font-black text-bali-navy text-lg">
                    Filter Motor
                </h3>

                <div>
                    <label class="text-xs font-bold text-bali-muted">Lokasi</label>
                    <input type="text"
                           name="delivery_location"
                           value="{{ request('delivery_location') }}"
                           class="input-ui"
                           placeholder="Kuta, Canggu...">
                </div>

                <div>
                    <label class="text-xs font-bold text-bali-muted">Tanggal</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-ui">
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-ui">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-bali-muted">Jenis Motor</label>
                    <select name="type" class="input-ui">
                        <option value="">Semua</option>
                        @foreach ($types as $type)
                            <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 pt-2">
                    <button class="btn-primary w-full">Filter</button>
                    <a href="{{ route('motorcycles.index') }}" class="btn-light w-full text-center">Reset</a>
                </div>

            </form>

        </aside>


        <!-- RESULT -->
        <div>

            <!-- HEADER -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">

                <div>
                    <h2 class="text-3xl font-black text-bali-navy">
                        Motor tersedia
                    </h2>
                    <p class="text-bali-muted mt-1">
                        {{ $motorcycles->count() }} unit ditemukan
                    </p>
                </div>

            </div>


            <!-- GRID -->
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">

                @forelse ($motorcycles as $motorcycle)
                    <article class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition">

                        <!-- IMAGE -->
                        <div class="relative h-52 bg-slate-50 flex items-center justify-center">

                            <span class="absolute top-3 left-3 bg-teal-50 text-teal-700 text-xs font-bold px-3 py-1 rounded-full">
                                Available
                            </span>

                            @if ($motorcycle->image)
                                <img src="{{ asset('storage/' . $motorcycle->image) }}"
                                     class="h-full object-contain transition group-hover:scale-105">
                            @else
                                <span class="text-bali-muted">
                                    {{ $motorcycle->brand }}
                                </span>
                            @endif
                        </div>


                        <!-- CONTENT -->
                        <div class="p-5">

                            <h3 class="font-black text-bali-navy">
                                {{ $motorcycle->brand }} {{ $motorcycle->model }}
                            </h3>

                            <p class="text-sm text-bali-muted mt-1">
                                {{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="badge">Helm</span>
                                <span class="badge">STNK</span>
                            </div>

                            <div class="mt-5 flex items-center justify-between">

                                <div>
                                    <strong class="text-lg font-black text-bali-navy">
                                        Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}
                                    </strong>
                                    <span class="text-sm text-bali-muted">/ hari</span>
                                </div>

                                <a href="{{ route('motorcycles.show', $motorcycle) }}"
                                   class="btn-primary px-4 py-2 text-sm">
                                    Detail
                                </a>

                            </div>

                        </div>

                    </article>

                @empty
                    <div class="col-span-full bg-white rounded-2xl p-12 text-center">
                        <h3 class="text-2xl font-black text-bali-navy">
                            Tidak ada motor ditemukan
                        </h3>
                        <p class="text-bali-muted mt-3">
                            Coba ubah filter.
                        </p>
                        <a href="{{ route('motorcycles.index') }}" class="btn-dark mt-6">
                            Reset
                        </a>
                    </div>
                @endforelse

            </div>

        </div>

    </div>
</section>

@endsection