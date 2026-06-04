@extends('layouts.public')

@section('content')
<section class="bg-gradient-to-br from-bali-navy via-slate-900 to-blue-950 py-16 text-white">
    <div class="mx-auto w-[min(1180px,92%)]">
        <span class="mb-3 inline-flex text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
            Pengajuan Sewa
        </span>
        <h1 class="text-4xl font-black tracking-[-0.04em]">
            Ajukan Sewa {{ $motorcycle->brand }} {{ $motorcycle->model }}
        </h1>
        <p class="mt-4 max-w-2xl leading-8 text-slate-300">
            Isi tanggal, lokasi pengantaran, dan catatan tambahan. Setelah dikirim, admin akan memeriksa pengajuan.
        </p>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto grid w-[min(1180px,92%)] gap-8 lg:grid-cols-[0.7fr_1fr]">
        <aside class="h-fit rounded-[2rem] border border-bali-line bg-white p-6 shadow-xl">
            <div class="flex h-64 items-center justify-center rounded-[1.5rem] bg-gradient-to-br from-cyan-50 to-slate-50 p-6 text-center font-black text-bali-navy">
                @if ($motorcycle->image)
                    <img src="{{ asset('storage/' . $motorcycle->image) }}" alt="{{ $motorcycle->brand }} {{ $motorcycle->model }}" class="h-full w-full object-contain">
                @else
                    {{ $motorcycle->brand }} {{ $motorcycle->model }}
                @endif
            </div>

            <div class="mt-6">
                <h2 class="text-2xl font-black text-bali-navy">
                    {{ $motorcycle->brand }} {{ $motorcycle->model }}
                </h2>
                <p class="mt-1 text-sm text-bali-muted">
                    {{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}
                </p>
            </div>

            <div class="mt-6 rounded-2xl bg-slate-100 p-5">
                <span class="block text-sm font-bold text-bali-muted">Harga per hari</span>
                <strong class="mt-2 block text-2xl font-black text-bali-navy">
                    Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}
                </strong>
            </div>

            <div class="mt-6 grid gap-3 text-sm text-bali-muted">
                <div class="flex justify-between gap-4">
                    <span>Plat</span>
                    <strong class="text-bali-navy">{{ $motorcycle->plate_number }}</strong>
                </div>
                <div class="flex justify-between gap-4">
                    <span>Status awal</span>
                    <strong class="text-bali-teal-dark">Menunggu Persetujuan</strong>
                </div>
            </div>
        </aside>

        <div class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
                    <strong class="block font-black">Periksa kembali input.</strong>
                    <span class="mt-1 block">Ada data yang belum sesuai.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('bookings.store', $motorcycle) }}" class="space-y-6">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="start_date" class="mb-2 block text-sm font-black text-bali-navy">
                            Tanggal Mulai
                        </label>
                        <input
                            id="start_date"
                            type="date"
                            name="start_date"
                            value="{{ old('start_date', request('start_date')) }}"
                            required
                            class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                        >
                        @error('start_date')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="mb-2 block text-sm font-black text-bali-navy">
                            Tanggal Selesai
                        </label>
                        <input
                            id="end_date"
                            type="date"
                            name="end_date"
                            value="{{ old('end_date', request('end_date')) }}"
                            required
                            class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                        >
                        @error('end_date')
                            <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
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
                        class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                    >
                    @error('delivery_location')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
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
                        placeholder="Contoh: Pengantaran pagi, lokasi detail, atau permintaan tambahan"
                        class="w-full rounded-2xl border border-bali-line px-4 py-3 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                    >{{ old('customer_note') }}</textarea>
                    @error('customer_note')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-[1.5rem] border border-teal-200 bg-teal-50 p-5">
                    <strong class="block text-bali-navy">Catatan sistem</strong>
                    <p class="mt-2 text-sm leading-7 text-bali-muted">
                        Total biaya akan dihitung otomatis oleh sistem berdasarkan jumlah hari sewa dan harga per hari.
                        Pengajuan ini belum berarti motor langsung disetujui, karena admin masih perlu melakukan verifikasi.
                    </p>
                </div>

                <div class="flex flex-col gap-3 border-t border-bali-line pt-6 sm:flex-row">
                    <button
                        type="submit"
                        class="rounded-full bg-bali-orange px-7 py-4 text-sm font-black text-white transition hover:bg-bali-orange-dark"
                    >
                        Kirim Pengajuan
                    </button>

                    <a href="{{ route('motorcycles.show', $motorcycle) }}"
                       class="rounded-full bg-slate-100 px-7 py-4 text-center text-sm font-black text-bali-navy transition hover:bg-slate-200">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection