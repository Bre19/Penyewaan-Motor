@extends('layouts.public')

@section('content')
<section class="relative overflow-hidden bg-bali-navy py-20 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(13,148,136,0.34),transparent_30rem),radial-gradient(circle_at_85%_5%,rgba(249,115,22,0.28),transparent_26rem)]"></div>

    <div class="container-page relative">
        <span class="badge-teal bg-white/10 text-teal-200">
            Pembayaran
        </span>
        <h1 class="mt-5 max-w-4xl text-5xl font-black leading-tight tracking-[-0.05em] md:text-6xl">
            Upload bukti pembayaran.
        </h1>
        <p class="mt-5 max-w-2xl leading-8 text-slate-300">
            Kirim bukti pembayaran agar admin dapat melakukan verifikasi.
        </p>
    </div>
</section>

<section class="py-16">
    <div class="container-page grid gap-8 lg:grid-cols-[0.72fr_1fr]">
        <aside class="surface-card h-fit rounded-[2rem] p-6">
            <span class="badge-orange">
                {{ $booking->booking_code }}
            </span>

            <h2 class="mt-5 text-3xl font-black text-bali-navy">
                {{ $booking->motorcycle->brand }} {{ $booking->motorcycle->model }}
            </h2>

            <p class="mt-2 font-semibold text-bali-muted">
                {{ $booking->start_date->translatedFormat('d M Y') }}
                -
                {{ $booking->end_date->translatedFormat('d M Y') }}
            </p>

            <div class="mt-6 rounded-[1.7rem] bg-bali-navy p-6 text-white">
                <span class="block text-sm font-bold text-slate-300">Total Pembayaran</span>
                <strong class="mt-2 block text-4xl font-black">
                    Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                </strong>
            </div>

            <div class="mt-6 rounded-2xl border border-orange-200 bg-orange-50 p-5">
                <strong class="block text-bali-navy">Instruksi</strong>
                <p class="mt-2 text-sm leading-7 text-bali-muted">
                    Upload bukti pembayaran dalam format JPG, PNG, atau PDF. Pastikan nominal dan nama pengirim terbaca jelas.
                </p>
            </div>
        </aside>

        <div class="surface-card rounded-[2rem] p-8">
            <div class="mb-8">
                <span class="badge-teal">Form Pembayaran</span>
                <h2 class="mt-4 text-3xl font-black text-bali-navy">Data pembayaran</h2>
                <p class="mt-2 text-sm leading-7 text-bali-muted">
                    Bukti pembayaran akan diperiksa oleh admin sebelum status booking diperbarui.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
                    <strong class="block font-black">Input belum sesuai.</strong>
                    <span class="mt-1 block">Periksa kembali data pembayaran.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('payments.store', $booking) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="method" class="mb-2 block text-sm font-black text-bali-navy">
                        Metode Pembayaran
                    </label>
                    <select id="method" name="method" required class="input-ui">
                        <option value="">Pilih metode</option>
                        @foreach (\App\Models\Payment::methodLabels() as $value => $label)
                            <option value="{{ $value }}" {{ old('method') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('method')
                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payer_name" class="mb-2 block text-sm font-black text-bali-navy">
                        Nama Pengirim / Pemilik Rekening
                    </label>
                    <input
                        id="payer_name"
                        type="text"
                        name="payer_name"
                        value="{{ old('payer_name') }}"
                        required
                        placeholder="Nama sesuai bukti pembayaran"
                        class="input-ui"
                    >
                    @error('payer_name')
                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="proof" class="mb-2 block text-sm font-black text-bali-navy">
                        Bukti Pembayaran
                    </label>
                    <input
                        id="proof"
                        type="file"
                        name="proof"
                        accept=".jpg,.jpeg,.png,.pdf"
                        required
                        class="w-full rounded-2xl border border-bali-line bg-white px-4 py-3 text-sm outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-bali-navy file:px-5 file:py-2 file:text-sm file:font-black file:text-white focus:border-bali-teal focus:ring-4 focus:ring-bali-teal/10"
                    >
                    <p class="mt-2 text-xs font-semibold text-bali-muted">Format: JPG, PNG, atau PDF. Maksimal 2MB.</p>
                    @error('proof')
                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="note" class="mb-2 block text-sm font-black text-bali-navy">
                        Catatan Pembayaran
                    </label>
                    <textarea
                        id="note"
                        name="note"
                        rows="4"
                        placeholder="Contoh: transfer dari BCA pukul 10.30"
                        class="textarea-ui"
                    >{{ old('note') }}</textarea>
                    @error('note')
                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 border-t border-bali-line pt-6 sm:flex-row">
                    <button type="submit" class="btn-primary">
                        Kirim Bukti Pembayaran
                    </button>

                    <a href="{{ route('bookings.show', $booking) }}" class="btn-light">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection