@extends('layouts.public')

@section('content')
@php
    $latestPayment = $booking->latestPayment;

    $badgeClass = match ($booking->status) {
        \App\Models\Booking::STATUS_PENDING_APPROVAL => 'bg-amber-50 text-amber-700 border-amber-200',
        \App\Models\Booking::STATUS_APPROVED,
        \App\Models\Booking::STATUS_WAITING_PAYMENT => 'bg-orange-50 text-orange-700 border-orange-200',
        \App\Models\Booking::STATUS_REJECTED => 'bg-red-50 text-red-700 border-red-200',
        \App\Models\Booking::STATUS_WAITING_PAYMENT_VERIFICATION => 'bg-purple-50 text-purple-700 border-purple-200',
        \App\Models\Booking::STATUS_PAYMENT_CONFIRMED => 'bg-teal-50 text-teal-700 border-teal-200',
        \App\Models\Booking::STATUS_READY_TO_DELIVER => 'bg-cyan-50 text-cyan-700 border-cyan-200',
        \App\Models\Booking::STATUS_ONGOING => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        \App\Models\Booking::STATUS_COMPLETED => 'bg-slate-100 text-slate-700 border-slate-200',
        \App\Models\Booking::STATUS_CANCELLED => 'bg-slate-100 text-slate-500 border-slate-200',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };
@endphp

<section class="relative overflow-hidden bg-bali-navy py-20 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(13,148,136,0.34),transparent_30rem),radial-gradient(circle_at_85%_5%,rgba(249,115,22,0.28),transparent_26rem)]"></div>

    <div class="container-page relative">
        <span class="badge-teal bg-white/10 text-teal-200">
            Detail Booking
        </span>

        <div class="mt-5 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h1 class="text-5xl font-black tracking-[-0.05em] md:text-6xl">
                    {{ $booking->booking_code }}
                </h1>
                <p class="mt-5 max-w-2xl leading-8 text-slate-300">
                    Pantau proses pengajuan, pembayaran, dan status penyewaan motor Anda.
                </p>
            </div>

            <span class="w-fit rounded-full border px-5 py-3 text-sm font-black {{ $badgeClass }}">
                {{ $booking->statusLabel() }}
            </span>
        </div>
    </div>
</section>

<section class="py-16">
    <div class="container-page grid gap-8 lg:grid-cols-[1fr_0.75fr]">
        <div class="space-y-8">
            @if (session('success'))
                <div class="rounded-2xl border border-teal-200 bg-teal-50 p-5 text-sm font-bold text-bali-teal-dark">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-sm font-bold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="surface-card rounded-[2rem] p-8">
                <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                    <div>
                        <span class="badge-orange">Unit Sewa</span>
                        <h2 class="mt-4 text-3xl font-black text-bali-navy">
                            {{ $booking->motorcycle->brand }} {{ $booking->motorcycle->model }}
                        </h2>
                        <p class="mt-2 font-semibold text-bali-muted">
                            {{ $booking->motorcycle->type ?? '-' }} • {{ $booking->motorcycle->year ?? '-' }}
                        </p>
                    </div>

                    <strong class="text-2xl font-black text-bali-navy">
                        Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="mt-8 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-bali-line p-5">
                        <span class="block text-sm text-bali-muted">Tanggal Mulai</span>
                        <strong class="mt-1 block text-bali-navy">{{ $booking->start_date->translatedFormat('d F Y') }}</strong>
                    </div>

                    <div class="rounded-2xl border border-bali-line p-5">
                        <span class="block text-sm text-bali-muted">Tanggal Selesai</span>
                        <strong class="mt-1 block text-bali-navy">{{ $booking->end_date->translatedFormat('d F Y') }}</strong>
                    </div>

                    <div class="rounded-2xl border border-bali-line p-5">
                        <span class="block text-sm text-bali-muted">Durasi</span>
                        <strong class="mt-1 block text-bali-navy">{{ $booking->duration_days }} hari</strong>
                    </div>

                    <div class="rounded-2xl border border-bali-line p-5">
                        <span class="block text-sm text-bali-muted">Lokasi Pengantaran</span>
                        <strong class="mt-1 block text-bali-navy">{{ $booking->delivery_location }}</strong>
                    </div>
                </div>

                @if ($booking->customer_note)
                    <div class="mt-8">
                        <h3 class="text-xl font-black text-bali-navy">Catatan Penyewa</h3>
                        <p class="mt-3 rounded-2xl bg-slate-100 p-5 leading-8 text-bali-muted">
                            {{ $booking->customer_note }}
                        </p>
                    </div>
                @endif
            </div>

            <div class="surface-card rounded-[2rem] p-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <span class="badge-teal">Pembayaran</span>
                        <h3 class="mt-4 text-2xl font-black text-bali-navy">Status pembayaran</h3>
                        <p class="mt-2 text-sm leading-7 text-bali-muted">
                            Upload bukti pembayaran setelah booking disetujui oleh admin.
                        </p>
                    </div>

                    @if ($booking->canUploadPaymentProof())
                        <a href="{{ route('payments.create', $booking) }}" class="btn-primary">
                            Upload Bukti
                        </a>
                    @endif
                </div>

                @if ($latestPayment)
                    <div class="mt-6 grid gap-4 md:grid-cols-3">
                        <div class="rounded-2xl bg-slate-100 p-4">
                            <span class="block text-sm text-bali-muted">Kode</span>
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
                    <div class="mt-6 rounded-2xl bg-slate-100 p-5 text-sm font-semibold text-bali-muted">
                        Belum ada bukti pembayaran yang dikirim.
                    </div>
                @endif
            </div>
        </div>

        <aside class="h-fit space-y-6">
            <div class="surface-card rounded-[2rem] p-6">
                <h3 class="text-2xl font-black text-bali-navy">Alur Status</h3>

                <div class="mt-6 grid gap-4">
                    @foreach ([
                        ['1', 'Pengajuan', 'Admin memeriksa data dan ketersediaan motor.'],
                        ['2', 'Pembayaran', 'Penyewa upload bukti pembayaran setelah disetujui.'],
                        ['3', 'Pengantaran', 'Motor disiapkan dan dikirim setelah pembayaran valid.'],
                    ] as [$number, $title, $description])
                        <div class="rounded-2xl bg-slate-100 p-5">
                            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-bali-navy text-sm font-black text-white">
                                {{ $number }}
                            </span>
                            <strong class="mt-4 block text-bali-navy">{{ $title }}</strong>
                            <p class="mt-2 text-sm leading-6 text-bali-muted">{{ $description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="surface-card rounded-[2rem] p-6">
                <h3 class="text-xl font-black text-bali-navy">Aksi</h3>

                <div class="mt-5 grid gap-3">
                    <a href="{{ route('dashboard') }}" class="btn-dark">
                        Kembali ke Dashboard
                    </a>

                    @if ($booking->canBeCancelledByCustomer())
                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="w-full rounded-full bg-red-50 px-7 py-4 text-sm font-black text-red-700 transition hover:bg-red-100"
                                onclick="return confirm('Batalkan booking ini?')"
                            >
                                Batalkan Booking
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </aside>
    </div>
</section>
@endsection