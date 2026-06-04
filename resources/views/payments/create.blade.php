@extends('layouts.public')

@section('content')
<section class="bg-gradient-to-br from-bali-navy via-slate-900 to-blue-950 py-16 text-white">
    <div class="mx-auto w-[min(1180px,92%)]">
        <span class="mb-3 inline-flex text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
            Pembayaran
        </span>
        <h1 class="text-4xl font-black tracking-[-0.04em]">
            Upload Bukti Pembayaran
        </h1>
        <p class="mt-4 max-w-2xl leading-8 text-slate-300">
            Kirim bukti pembayaran agar admin dapat melakukan verifikasi.
        </p>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto grid w-[min(1180px,92%)] gap-8 lg:grid-cols-[0.7fr_1fr]">
        <aside class="h-fit rounded-[2rem] border border-bali-line bg-white p-6 shadow-xl">
            <span class="text-sm font-black uppercase tracking-wide text-bali-teal">
                {{ $booking->booking_code }}
            </span>

            <h2 class="mt-3 text-2xl font-black text-bali-navy">
                {{ $booking->motorcycle->brand }} {{ $booking->motorcycle->model }}
            </h2>

            <p class="mt-2 text-sm text-bali-muted">
                {{ $booking->start_date->translatedFormat('d M Y') }}
                -
                {{ $booking->end_date->translatedFormat('d M Y') }}
            </p>

            <div class="mt-6 rounded-[1.5rem] bg-slate-100 p-5">
                <span class="block text-sm font-bold text-bali-muted">Total Pembayaran</span>
                <strong class="mt-2 block text-3xl font-black text-bali-navy">
                    Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                </strong>
            </div>

            <div class="mt-6 rounded-2xl border border-orange-200 bg-orange-50 p-5">
                <strong class="block text-bali-navy">Instruksi</strong>
                <p class="mt-2 text-sm leading-7 text-bali-muted">
                    Lakukan pembayaran sesuai nominal booking, lalu upload bukti transfer dalam format JPG, PNG, atau PDF.
                </p>
            </div>
        </aside>

        <div class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
                    <strong class="block font-black">Periksa kembali input.</strong>
                    <span class="mt-1 block">Ada data pembayaran yang belum sesuai.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('payments.store', $booking) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label for="method" class="mb-2 block text-sm font-black text-bali-navy">
                        Metode Pembayaran
                    </label>
                    <select
                        id="method"
                        name="method"
                        required
                        class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                    >
                        <option value="">Pilih metode</option>
                        @foreach (\App\Models\Payment::methodLabels() as $value => $label)
                            <option value="{{ $value }}" {{ old('method') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('method')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
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
                        class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                    >
                    @error('payer_name')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
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
                        class="w-full rounded-2xl border border-bali-line bg-white px-4 py-3 text-sm outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-bali-navy file:px-5 file:py-2 file:text-sm file:font-black file:text-white focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                    >
                    <p class="mt-2 text-xs text-bali-muted">Format: JPG, PNG, atau PDF. Maksimal 2MB.</p>
                    @error('proof')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
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
                        class="w-full rounded-2xl border border-bali-line px-4 py-3 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                    >{{ old('note') }}</textarea>
                    @error('note')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col gap-3 border-t border-bali-line pt-6 sm:flex-row">
                    <button
                        type="submit"
                        class="rounded-full bg-bali-orange px-7 py-4 text-sm font-black text-white transition hover:bg-bali-orange-dark"
                    >
                        Kirim Bukti Pembayaran
                    </button>

                    <a href="{{ route('bookings.show', $booking) }}"
                       class="rounded-full bg-slate-100 px-7 py-4 text-center text-sm font-black text-bali-navy transition hover:bg-slate-200">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection