@extends('layouts.admin')

@section('page-title', 'Detail Pembayaran')

@section('content')
@if (session('success'))
    <div class="mb-8 rounded-2xl border border-teal-200 bg-teal-50 p-5 text-sm font-semibold text-bali-teal-dark">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm font-semibold text-red-700">
        {{ session('error') }}
    </div>
@endif

<div class="grid gap-8 xl:grid-cols-[1fr_380px]">
    <section class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
        <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
            <div>
                <span class="text-sm font-black uppercase tracking-wide text-bali-teal">
                    {{ $payment->payment_code }}
                </span>
                <h2 class="mt-2 text-3xl font-black text-bali-navy">
                    Pembayaran {{ $payment->booking->booking_code }}
                </h2>
                <p class="mt-2 text-bali-muted">
                    {{ $payment->booking->motorcycle->brand }} {{ $payment->booking->motorcycle->model }}
                    • {{ $payment->booking->motorcycle->plate_number }}
                </p>
            </div>

            <span class="w-fit rounded-full bg-slate-100 px-5 py-3 text-xs font-black text-bali-navy">
                {{ $payment->statusLabel() }}
            </span>
        </div>

        <div class="mt-8 grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl border border-bali-line p-5">
                <span class="block text-sm text-bali-muted">Penyewa</span>
                <strong class="mt-1 block text-bali-navy">{{ $payment->user->name }}</strong>
                <span class="mt-1 block text-sm text-bali-muted">{{ $payment->user->email }}</span>
            </div>

            <div class="rounded-2xl border border-bali-line p-5">
                <span class="block text-sm text-bali-muted">Nama Pengirim</span>
                <strong class="mt-1 block text-bali-navy">{{ $payment->payer_name }}</strong>
            </div>

            <div class="rounded-2xl border border-bali-line p-5">
                <span class="block text-sm text-bali-muted">Metode</span>
                <strong class="mt-1 block text-bali-navy">{{ $payment->methodLabel() }}</strong>
            </div>

            <div class="rounded-2xl border border-bali-line p-5">
                <span class="block text-sm text-bali-muted">Tanggal Upload</span>
                <strong class="mt-1 block text-bali-navy">
                    {{ $payment->uploaded_at?->translatedFormat('d F Y H:i') ?? '-' }}
                </strong>
            </div>
        </div>

        <div class="mt-8 rounded-[1.5rem] bg-slate-100 p-6">
            <span class="block text-sm font-bold text-bali-muted">Nominal Pembayaran</span>
            <strong class="mt-2 block text-3xl font-black text-bali-navy">
                Rp{{ number_format($payment->amount, 0, ',', '.') }}
            </strong>
        </div>

        <div class="mt-8">
            <h3 class="text-xl font-black text-bali-navy">Bukti Pembayaran</h3>

            <div class="mt-4 rounded-[1.5rem] border border-bali-line bg-slate-50 p-5">
                <a href="{{ asset('storage/' . $payment->proof_path) }}"
                   target="_blank"
                   class="inline-flex rounded-full bg-bali-navy px-6 py-3 text-sm font-black text-white transition hover:bg-bali-slate">
                    Buka Bukti Pembayaran
                </a>

                <p class="mt-3 text-sm leading-7 text-bali-muted">
                    Bukti pembayaran akan terbuka di tab baru. Pastikan nominal, nama pengirim, dan waktu pembayaran sesuai.
                </p>
            </div>
        </div>

        @if ($payment->note)
            <div class="mt-8">
                <h3 class="text-xl font-black text-bali-navy">Catatan Penyewa</h3>
                <p class="mt-4 rounded-2xl border border-bali-line p-5 leading-8 text-bali-muted">
                    {{ $payment->note }}
                </p>
            </div>
        @endif

        @if ($payment->rejection_reason)
            <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 p-5">
                <strong class="block text-red-700">Alasan Penolakan</strong>
                <p class="mt-2 text-sm leading-7 text-red-700">{{ $payment->rejection_reason }}</p>
            </div>
        @endif
    </section>

    <aside class="h-fit rounded-[2rem] border border-bali-line bg-white p-6 shadow-xl">
        <h3 class="text-2xl font-black text-bali-navy">Aksi Admin</h3>
        <p class="mt-2 text-sm leading-7 text-bali-muted">
            Konfirmasi pembayaran jika bukti valid. Tolak jika nominal, nama, atau bukti tidak sesuai.
        </p>

        @if ($payment->status === \App\Models\Payment::STATUS_WAITING_VERIFICATION)
            <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}" class="mt-6">
                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="w-full rounded-full bg-bali-teal px-6 py-4 text-sm font-black text-white transition hover:bg-bali-teal-dark"
                    onclick="return confirm('Konfirmasi pembayaran ini?')"
                >
                    Konfirmasi Pembayaran
                </button>
            </form>

            <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" class="mt-5">
                @csrf
                @method('PATCH')

                <label for="rejection_reason" class="mb-2 block text-sm font-black text-bali-navy">
                    Alasan Penolakan
                </label>

                <textarea
                    id="rejection_reason"
                    name="rejection_reason"
                    rows="5"
                    required
                    placeholder="Contoh: nominal tidak sesuai atau bukti pembayaran tidak jelas."
                    class="w-full rounded-2xl border border-bali-line px-4 py-3 text-sm outline-none transition focus:border-red-400 focus:ring-2 focus:ring-red-100"
                >{{ old('rejection_reason') }}</textarea>

                @error('rejection_reason')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror

                <button
                    type="submit"
                    class="mt-4 w-full rounded-full bg-red-50 px-6 py-4 text-sm font-black text-red-700 transition hover:bg-red-100"
                    onclick="return confirm('Tolak pembayaran ini?')"
                >
                    Tolak Pembayaran
                </button>
            </form>
        @else
            <div class="mt-6 rounded-2xl bg-slate-100 p-5 text-sm leading-7 text-bali-muted">
                Pembayaran ini sudah diproses. Aksi hanya tersedia untuk status
                <strong class="text-bali-navy">Menunggu Verifikasi</strong>.
            </div>
        @endif

        <a href="{{ route('admin.payments.index') }}"
           class="mt-5 block rounded-full bg-bali-navy px-6 py-4 text-center text-sm font-black text-white transition hover:bg-bali-slate">
            Kembali ke Daftar
        </a>
    </aside>
</div>
@endsection