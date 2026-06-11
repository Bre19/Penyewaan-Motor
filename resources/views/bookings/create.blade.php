@extends('layouts.public')

@section('content')
<section class="relative overflow-hidden bg-bali-navy py-20 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(13,148,136,0.34),transparent_30rem),radial-gradient(circle_at_85%_5%,rgba(249,115,22,0.28),transparent_26rem)]"></div>

    <div class="container-page relative">
        <span class="badge-teal bg-white/10 text-teal-200">
            Pengajuan Sewa
        </span>
        <h1 class="mt-5 max-w-4xl text-5xl font-black leading-tight tracking-[-0.05em] md:text-6xl">
            Ajukan sewa {{ $motorcycle->brand }} {{ $motorcycle->model }}
        </h1>
        <p class="mt-5 max-w-2xl leading-8 text-slate-300">
            Isi detail tanggal, lokasi pengantaran, dan catatan tambahan untuk admin.
        </p>
    </div>
</section>

<section class="py-16">
    <div class="container-page grid gap-8 lg:grid-cols-[0.72fr_1fr]">
        <aside class="surface-card h-fit rounded-[2rem] p-6">
            <div class="relative flex h-72 items-center justify-center overflow-hidden rounded-[1.7rem] bg-gradient-to-br from-slate-100 via-white to-orange-50 p-6 text-center font-black text-bali-navy">
                <span class="absolute left-5 top-5 rounded-full bg-white px-4 py-2 text-xs font-black text-bali-teal-dark shadow-sm">
                    Ready
                </span>

                @if ($motorcycle->image)
                    <img src="{{ asset('storage/' . $motorcycle->image) }}" alt="{{ $motorcycle->brand }} {{ $motorcycle->model }}" class="h-full w-full object-contain">
                @else
                    {{ $motorcycle->brand }} {{ $motorcycle->model }}
                @endif
            </div>

            <div class="mt-6">
                <h2 class="text-3xl font-black text-bali-navy">
                    {{ $motorcycle->brand }} {{ $motorcycle->model }}
                </h2>
                <p class="mt-2 font-semibold text-bali-muted">
                    {{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}
                </p>
            </div>

            <div class="mt-6 rounded-[1.5rem] bg-bali-navy p-6 text-white">
                <span class="block text-sm font-bold text-slate-300">Harga per hari</span>
                <strong class="mt-2 block text-3xl font-black">
                    Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}
                </strong>
            </div>

            <div class="mt-6 grid gap-3 text-sm">
                <div class="flex justify-between gap-4 rounded-2xl bg-slate-100 p-4">
                    <span class="font-bold text-bali-muted">Plat</span>
                    <strong class="text-bali-navy">{{ $motorcycle->plate_number }}</strong>
                </div>
                <div class="flex justify-between gap-4 rounded-2xl bg-slate-100 p-4">
                    <span class="font-bold text-bali-muted">Status awal</span>
                    <strong class="text-bali-teal-dark">Menunggu Persetujuan</strong>
                </div>
            </div>
        </aside>

        <div class="surface-card rounded-[2rem] p-8">
            <div class="mb-8">
                <span class="badge-orange">Form Booking</span>
                <h2 class="mt-4 text-3xl font-black text-bali-navy">Detail pengajuan</h2>
                <p class="mt-2 text-sm leading-7 text-bali-muted">
                    Pastikan tanggal dan lokasi pengantaran sudah benar sebelum dikirim.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
                    <strong class="block font-black">Input belum sesuai.</strong>
                    <span class="mt-1 block">Periksa kembali data yang ditandai.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('bookings.store', $motorcycle) }}" class="space-y-6">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="start_date" class="mb-2 block text-sm font-black text-bali-navy">
                            Tanggal Mulai
                        </label>
                        <input id="start_date" type="date" name="start_date" value="{{ old('start_date', request('start_date')) }}" required class="input-ui">
                        @error('start_date')
                            <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="mb-2 block text-sm font-black text-bali-navy">
                            Tanggal Selesai
                        </label>
                        <input id="end_date" type="date" name="end_date" value="{{ old('end_date', request('end_date')) }}" required class="input-ui">
                        @error('end_date')
                            <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="delivery_location" class="mb-2 block text-sm font-black text-bali-navy">
                        Lokasi Pengantaran
                    </label>
                    <input
                        id="delivery_location"
                        type="text"
                        name="delivery_location"
                        value="{{ old('delivery_location', request('delivery_location')) }}"
                        required
                        placeholder="Contoh: Hotel area Kuta, Canggu, Ubud"
                        class="input-ui"
                    >
                    @error('delivery_location')
                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_note" class="mb-2 block text-sm font-black text-bali-navy">
                        Catatan Tambahan
                    </label>
                    <textarea
                        id="customer_note"
                        name="customer_note"
                        rows="4"
                        placeholder="Contoh: pengantaran pagi, lokasi detail, atau permintaan tambahan"
                        class="textarea-ui"
                    >{{ old('customer_note') }}</textarea>
                    @error('customer_note')
                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-[1.5rem] border border-teal-200 bg-teal-50 p-5">
                    <strong class="block text-bali-navy">Catatan sistem</strong>
                    <p class="mt-2 text-sm leading-7 text-bali-muted">
                        Total biaya dihitung otomatis berdasarkan jumlah hari sewa dan harga per hari.
                        Booking belum aktif sampai admin menyetujui pengajuan.
                    </p>
                </div>

                <div class="flex flex-col gap-3 border-t border-bali-line pt-6 sm:flex-row">
                    <button type="submit" class="btn-primary">
                        Kirim Pengajuan
                    </button>

                    <a href="{{ route('motorcycles.show', $motorcycle) }}" class="btn-light">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection