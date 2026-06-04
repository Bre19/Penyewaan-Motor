@extends('layouts.public')

@section('content')
@php
    $badgeClass = match ($booking->status) {
        \App\Models\Booking::STATUS_PENDING_APPROVAL => 'bg-amber-50 text-amber-700 border-amber-200',
        \App\Models\Booking::STATUS_APPROVED => 'bg-blue-50 text-blue-700 border-blue-200',
        \App\Models\Booking::STATUS_REJECTED => 'bg-red-50 text-red-700 border-red-200',
        \App\Models\Booking::STATUS_WAITING_PAYMENT => 'bg-orange-50 text-orange-700 border-orange-200',
        \App\Models\Booking::STATUS_WAITING_PAYMENT_VERIFICATION => 'bg-purple-50 text-purple-700 border-purple-200',
        \App\Models\Booking::STATUS_PAYMENT_CONFIRMED => 'bg-teal-50 text-teal-700 border-teal-200',
        \App\Models\Booking::STATUS_READY_TO_DELIVER => 'bg-cyan-50 text-cyan-700 border-cyan-200',
        \App\Models\Booking::STATUS_ONGOING => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        \App\Models\Booking::STATUS_COMPLETED => 'bg-slate-100 text-slate-700 border-slate-200',
        \App\Models\Booking::STATUS_CANCELLED => 'bg-slate-100 text-slate-500 border-slate-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };
@endphp

<section class="bg-gradient-to-br from-bali-navy via-slate-900 to-blue-950 py-16 text-white">
    <div class="mx-auto w-[min(1180px,92%)]">
        <span class="mb-3 inline-flex text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
            Detail Booking
        </span>
        <h1 class="text-4xl font-black tracking-[-0.04em]">
            {{ $booking->booking_code }}
        </h1>
        <p class="mt-4 max-w-2xl leading-8 text-slate-300">
            Pantau status pengajuan penyewaan motor Anda dari halaman ini.
        </p>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto grid w-[min(1180px,92%)] gap-8 lg:grid-cols-[1fr_0.75fr]">
        <div class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-teal-200 bg-teal-50 p-5 text-sm font-semibold text-bali-teal-dark">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-3xl font-black text-bali-navy">
                        {{ $booking->motorcycle->brand }} {{ $booking->motorcycle->model }}
                    </h2>
                    <p class="mt-2 text-bali-muted">
                        {{ $booking->motorcycle->type ?? '-' }} • {{ $booking->motorcycle->year ?? '-' }}
                    </p>
                </div>

                <span class="w-fit rounded-full border px-4 py-2 text-xs font-black {{ $badgeClass }}">
                    {{ $booking->statusLabel() }}
                </span>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-bali-line p-5">
                    <span class="block text-sm text-bali-muted">Tanggal Mulai</span>
                    <strong class="mt-1 block text-bali-navy">
                        {{ $booking->start_date->translatedFormat('d F Y') }}
                    </strong>
                </div>

                <div class="rounded-2xl border border-bali-line p-5">
                    <span class="block text-sm text-bali-muted">Tanggal Selesai</span>
                    <strong class="mt-1 block text-bali-navy">
                        {{ $booking->end_date->translatedFormat('d F Y') }}
                    </strong>
                </div>

                <div class="rounded-2xl border border-bali-line p-5">
                    <span class="block text-sm text-bali-muted">Durasi</span>
                    <strong class="mt-1 block text-bali-navy">
                        {{ $booking->duration_days }} hari
                    </strong>
                </div>

                <div class="rounded-2xl border border-bali-line p-5">
                    <span class="block text-sm text-bali-muted">Lokasi Pengantaran</span>
                    <strong class="mt-1 block text-bali-navy">
                        {{ $booking->delivery_location }}
                    </strong>
                </div>
            </div>

            <div class="mt-8 rounded-[1.5rem] bg-slate-100 p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <span class="block text-sm font-bold text-bali-muted">Total estimasi biaya</span>
                        <strong class="mt-2 block text-3xl font-black text-bali-navy">
                            Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                        </strong>
                    </div>
                    <span class="text-sm text-bali-muted">
                        Rp{{ number_format($booking->price_per_day, 0, ',', '.') }} × {{ $booking->duration_days }} hari
                    </span>
                </div>
            </div>

            @if ($booking->customer_note)
                <div class="mt-8">
                    <h3 class="text-xl font-black text-bali-navy">Catatan Penyewa</h3>
                    <p class="mt-3 rounded-2xl border border-bali-line p-5 leading-8 text-bali-muted">
                        {{ $booking->customer_note }}
                    </p>
                </div>
            @endif

            @php
                $latestPayment = $booking->latestPayment;
            @endphp

            <div class="mt-8 rounded-[1.7rem] border border-bali-line bg-white p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-xl font-black text-bali-navy">Pembayaran</h3>
                        <p class="mt-2 text-sm leading-7 text-bali-muted">
                            Upload bukti pembayaran setelah booking disetujui oleh admin.
                        </p>
                    </div>

                    @if ($booking->canUploadPaymentProof())
                        <a href="{{ route('payments.create', $booking) }}"
                        class="rounded-full bg-bali-orange px-6 py-3 text-center text-sm font-black text-white transition hover:bg-bali-orange-dark">
                            Upload Bukti Pembayaran
                        </a>
                    @endif
                </div>

                @if ($latestPayment)
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-2xl bg-slate-100 p-4">
                            <span class="block text-sm text-bali-muted">Kode Pembayaran</span>
                            <strong class="mt-1 block text-bali-navy">{{ $latestPayment->payment_code }}</strong>
                        </div>

                        <div class="rounded-2xl bg-slate-100 p-4">
                            <span class="block text-sm text-bali-muted">Metode</span>
                            <strong class="mt-1 block text-bali-navy">{{ $latestPayment->methodLabel() }}</strong>
                        </div>

                        <div class="rounded-2xl bg-slate-100 p-4">
                            <span class="block text-sm text-bali-muted">Status</span>
                            <strong class="mt-1 block text-bali-navy">{{ $latestPayment->statusLabel() }}</strong>
                        </div>
                    </div>

                    @if ($latestPayment->rejection_reason)
                        <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 p-5">
                            <strong class="block text-red-700">Alasan Penolakan Pembayaran</strong>
                            <p class="mt-2 text-sm leading-7 text-red-700">{{ $latestPayment->rejection_reason }}</p>
                        </div>
                    @endif
                @else
                    <div class="mt-6 rounded-2xl bg-slate-100 p-5 text-sm text-bali-muted">
                        Belum ada bukti pembayaran yang dikirim.
                    </div>
                @endif
            </div>

            <div class="mt-8 flex flex-col gap-3 border-t border-bali-line pt-8 sm:flex-row">
                <a href="{{ route('dashboard') }}"
                   class="rounded-full bg-bali-navy px-7 py-4 text-center text-sm font-black text-white transition hover:bg-bali-slate">
                    Kembali ke Dashboard
                </a>

                @if ($booking->canBeCancelledByCustomer())
                    <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full rounded-full bg-red-50 px-7 py-4 text-sm font-black text-red-700 transition hover:bg-red-100 sm:w-auto"
                            onclick="return confirm('Batalkan booking ini?')"
                        >
                            Batalkan Booking
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <aside class="h-fit rounded-[2rem] border border-bali-line bg-white p-6 shadow-xl">
            <h3 class="text-xl font-black text-bali-navy">Alur Selanjutnya</h3>

            <div class="mt-6 grid gap-4">
                <div class="rounded-2xl border border-bali-line p-5">
                    <strong class="text-bali-navy">1. Admin memeriksa pengajuan</strong>
                    <p class="mt-2 text-sm leading-6 text-bali-muted">
                        Admin memeriksa data penyewa, dokumen, dan ketersediaan motor.
                    </p>
                </div>

                <div class="rounded-2xl border border-bali-line p-5">
                    <strong class="text-bali-navy">2. Status disetujui atau ditolak</strong>
                    <p class="mt-2 text-sm leading-6 text-bali-muted">
                        Jika disetujui, status akan masuk ke tahap pembayaran.
                    </p>
                </div>

                <div class="rounded-2xl border border-bali-line p-5">
                    <strong class="text-bali-navy">3. Pembayaran dan pengantaran</strong>
                    <p class="mt-2 text-sm leading-6 text-bali-muted">
                        Setelah pembayaran valid, motor disiapkan untuk diantar.
                    </p>
                </div>
            </div>
        </aside>
    </div>
</section>
@endsection