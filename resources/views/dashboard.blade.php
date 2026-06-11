@extends('layouts.public')

@section('content')
@php
    $activeBadgeClass = $activeBooking
        ? match ($activeBooking->status) {
            \App\Models\Booking::STATUS_PENDING_APPROVAL => 'bg-amber-50 text-amber-700 border-amber-200',
            \App\Models\Booking::STATUS_APPROVED,
            \App\Models\Booking::STATUS_WAITING_PAYMENT => 'bg-orange-50 text-orange-700 border-orange-200',
            \App\Models\Booking::STATUS_WAITING_PAYMENT_VERIFICATION => 'bg-purple-50 text-purple-700 border-purple-200',
            \App\Models\Booking::STATUS_PAYMENT_CONFIRMED => 'bg-teal-50 text-teal-700 border-teal-200',
            \App\Models\Booking::STATUS_READY_TO_DELIVER => 'bg-cyan-50 text-cyan-700 border-cyan-200',
            \App\Models\Booking::STATUS_ONGOING => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        }
        : 'bg-slate-100 text-slate-700 border-slate-200';
@endphp

<section class="relative overflow-hidden bg-bali-navy py-20 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_15%,rgba(13,148,136,0.34),transparent_30rem),radial-gradient(circle_at_85%_10%,rgba(249,115,22,0.25),transparent_28rem)]"></div>
    <div class="container-page relative grid gap-8 lg:grid-cols-[1fr_0.72fr] lg:items-end">
        <div><span class="badge-teal bg-white/10 text-teal-100">My Rental Hub</span><h1 class="mt-5 text-5xl font-black leading-tight tracking-[-0.05em] md:text-6xl">Halo, {{ $user->name }}</h1><p class="mt-5 max-w-2xl leading-8 text-slate-300">Pantau booking aktif, pembayaran, safety badge, dan riwayat sewa dari satu dashboard.</p></div>
        <div class="glass-panel rounded-[2rem] p-6"><span class="text-xs font-black uppercase tracking-[0.18em] text-teal-200">Status Akun</span><strong class="mt-3 block text-2xl font-black">{{ $profileCompleted ? 'Data Awal Lengkap' : 'Data Belum Lengkap' }}</strong><p class="mt-2 text-sm leading-7 text-slate-300">Data profil menjadi dasar verifikasi admin saat pengajuan sewa.</p></div>
    </div>
</section>

<section class="py-16">
    <div class="container-page">
        @if (session('success'))<div class="mb-8 rounded-2xl border border-teal-200 bg-teal-50 p-5 text-sm font-bold text-bali-teal-dark">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="mb-8 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm font-bold text-red-700">{{ session('error') }}</div>@endif

        <div class="grid gap-6 md:grid-cols-3">
            <div class="surface-card rounded-[1.7rem] p-6"><span class="text-sm font-bold text-bali-muted">Total Pengajuan</span><strong class="mt-3 block text-4xl font-black text-bali-navy">{{ $bookingStats['total'] }}</strong><p class="mt-3 text-sm leading-6 text-bali-muted">Seluruh booking yang pernah dibuat.</p></div>
            <div class="surface-card rounded-[1.7rem] p-6"><span class="text-sm font-bold text-bali-muted">Booking Aktif</span><strong class="mt-3 block text-4xl font-black text-bali-navy">{{ $bookingStats['active'] }}</strong><p class="mt-3 text-sm leading-6 text-bali-muted">Masih berjalan atau menunggu proses.</p></div>
            <div class="surface-card rounded-[1.7rem] p-6"><span class="text-sm font-bold text-bali-muted">Sewa Selesai</span><strong class="mt-3 block text-4xl font-black text-bali-navy">{{ $bookingStats['completed'] }}</strong><p class="mt-3 text-sm leading-6 text-bali-muted">Riwayat rental yang sudah selesai.</p></div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_0.75fr]">
            <div class="surface-card rounded-[2rem] p-8">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"><div><span class="badge-orange">Booking Aktif</span><h2 class="mt-4 text-3xl font-black text-bali-navy">Status penyewaan terbaru</h2></div><a href="{{ route('motorcycles.index') }}" class="btn-primary">Cari Motor</a></div>

                @if ($activeBooking)
                    <div class="mt-8 rounded-[1.8rem] border border-bali-line bg-white p-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between"><div><span class="text-sm font-black uppercase tracking-wide text-bali-teal">{{ $activeBooking->booking_code }}</span><h3 class="mt-2 text-3xl font-black text-bali-navy">{{ $activeBooking->motorcycle->brand }} {{ $activeBooking->motorcycle->model }}</h3><p class="mt-2 font-semibold text-bali-muted">{{ $activeBooking->start_date->translatedFormat('d F Y') }} - {{ $activeBooking->end_date->translatedFormat('d F Y') }}</p></div><span class="w-fit rounded-full border px-4 py-2 text-xs font-black {{ $activeBadgeClass }}">{{ $activeBooking->statusLabel() }}</span></div>
                        <div class="mt-6 grid gap-4 md:grid-cols-3"><div class="rounded-2xl bg-slate-100 p-4"><span class="block text-sm text-bali-muted">Durasi</span><strong class="mt-1 block text-bali-navy">{{ $activeBooking->duration_days }} hari</strong></div><div class="rounded-2xl bg-slate-100 p-4"><span class="block text-sm text-bali-muted">Total</span><strong class="mt-1 block text-bali-navy">Rp{{ number_format($activeBooking->total_price, 0, ',', '.') }}</strong></div><div class="rounded-2xl bg-slate-100 p-4"><span class="block text-sm text-bali-muted">Pembayaran</span><strong class="mt-1 block text-bali-navy">{{ $activeBooking->latestPayment?->statusLabel() ?? 'Belum Ada' }}</strong></div></div>
                        <div class="mt-6 flex flex-wrap gap-3"><a href="{{ route('bookings.show', $activeBooking) }}" class="btn-dark">Lihat Detail</a>@if ($activeBooking->canUploadPaymentProof())<a href="{{ route('payments.create', $activeBooking) }}" class="btn-primary">Upload Pembayaran</a>@endif</div>
                    </div>
                @else
                    <div class="mt-8 rounded-[1.8rem] border border-dashed border-bali-line bg-white p-10 text-center"><h3 class="text-2xl font-black text-bali-navy">Belum ada booking aktif</h3><p class="mt-3 text-bali-muted">Pilih motor dari katalog lalu ajukan penyewaan.</p><a href="{{ route('motorcycles.index') }}" class="btn-dark mt-6">Lihat Katalog</a></div>
                @endif
            </div>

            <aside class="surface-card rounded-[2rem] p-8">
                <span class="badge-teal">Safety Profile</span><h2 class="mt-4 text-2xl font-black text-bali-navy">Data verifikasi & badge</h2>
                @if ($user->hasTrustedRiderBadge())<div class="mt-5 rounded-[1.5rem] border border-teal-200 bg-teal-50 p-5"><span class="block text-xs font-black uppercase tracking-[0.18em] text-bali-teal-dark">Badge Aktif</span><strong class="mt-2 block text-xl font-black text-bali-navy">Trusted Rider</strong><p class="mt-2 text-sm leading-7 text-bali-muted">Anda memiliki riwayat berkendara aman berdasarkan evaluasi rental sebelumnya.</p></div>@else<div class="mt-5 rounded-[1.5rem] border border-orange-200 bg-orange-50 p-5"><span class="block text-xs font-black uppercase tracking-[0.18em] text-bali-orange-dark">Badge Belum Aktif</span><strong class="mt-2 block text-xl font-black text-bali-navy">Selesaikan rental aman</strong><p class="mt-2 text-sm leading-7 text-bali-muted">Trusted Rider diberikan setelah rental selesai dengan Safety Score aman.</p></div>@endif
                <div class="mt-6 grid gap-3"><div class="flex items-center justify-between rounded-2xl bg-slate-100 p-4"><span class="text-sm font-bold text-bali-muted">Telepon</span><strong class="text-sm text-bali-navy">{{ $user->phone_number ?: '-' }}</strong></div><div class="flex items-center justify-between rounded-2xl bg-slate-100 p-4"><span class="text-sm font-bold text-bali-muted">Paspor</span><strong class="text-sm text-bali-navy">{{ filled($user->passport_number) ? 'Ada' : 'Belum Ada' }}</strong></div><div class="flex items-center justify-between rounded-2xl bg-slate-100 p-4"><span class="text-sm font-bold text-bali-muted">SIM</span><strong class="text-sm text-bali-navy">{{ $user->has_license ? 'Ada' : 'Tidak Ada' }}</strong></div></div>
            </aside>
        </div>

        <div class="mt-8 surface-card rounded-[2rem] p-8"><span class="badge-teal">Riwayat</span><h2 class="mt-4 text-3xl font-black text-bali-navy">Riwayat Booking</h2><div class="mt-6 overflow-hidden rounded-[1.5rem] border border-bali-line bg-white">@forelse ($latestBookings as $booking)<div class="flex flex-col gap-4 border-b border-bali-line p-5 last:border-b-0 md:flex-row md:items-center md:justify-between"><div><span class="text-sm font-black uppercase tracking-wide text-bali-teal">{{ $booking->booking_code }}</span><h3 class="mt-1 text-lg font-black text-bali-navy">{{ $booking->motorcycle->brand }} {{ $booking->motorcycle->model }}</h3><p class="mt-1 text-sm font-semibold text-bali-muted">{{ $booking->start_date->translatedFormat('d M Y') }} - {{ $booking->end_date->translatedFormat('d M Y') }}</p></div><div class="flex flex-col gap-3 md:items-end"><strong class="text-bali-navy">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</strong><a href="{{ route('bookings.show', $booking) }}" class="btn-light px-5 py-2.5">Detail</a></div></div>@empty<div class="p-10 text-center text-bali-muted">Belum ada riwayat booking.</div>@endforelse</div></div>
    </div>
</section>
@endsection
