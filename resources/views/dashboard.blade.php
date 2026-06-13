@extends('layouts.public')

@section('content')
@php
    $activeBadgeClass = $activeBooking
        ? match ($activeBooking->status) {
            \App\Models\Booking::STATUS_PENDING_APPROVAL => 'bg-amber-100 text-amber-700',
            \App\Models\Booking::STATUS_APPROVED,
            \App\Models\Booking::STATUS_WAITING_PAYMENT => 'bg-orange-100 text-orange-700',
            \App\Models\Booking::STATUS_WAITING_PAYMENT_VERIFICATION => 'bg-purple-100 text-purple-700',
            \App\Models\Booking::STATUS_PAYMENT_CONFIRMED => 'bg-teal-100 text-teal-700',
            \App\Models\Booking::STATUS_READY_TO_DELIVER => 'bg-cyan-100 text-cyan-700',
            \App\Models\Booking::STATUS_ONGOING => 'bg-emerald-100 text-emerald-700',
            default => 'bg-slate-100 text-slate-700',
        }
        : 'bg-slate-100 text-slate-700';
@endphp

{{-- HEADER --}}
<section class="bg-bali-navy text-white py-14 relative overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-teal-500/20 blur-[160px] rounded-full"></div>
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-orange-500/20 blur-[120px] rounded-full"></div>
    </div>

    <div class="container-page relative flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-black">Dashboard</h1>
            <p class="text-slate-300 mt-2">Kontrol penyewaan motor Anda</p>
        </div>

        <div class="glass-panel px-6 py-4 rounded-2xl">
            <div class="text-xs text-teal-200 uppercase">Status</div>
            <div class="font-bold">
                {{ $profileCompleted ? 'Lengkap' : 'Belum Lengkap' }}
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-gradient-to-b from-white to-bali-soft">
<div class="container-page">

{{-- STATS WITH ACTION --}}
<div class="grid md:grid-cols-3 gap-6">
    <a href="{{ route('bookings.index') }}"
       class="group dashboard-card">
        <div class="label">Total Booking</div>
        <div class="value">{{ $bookingStats['total'] }}</div>
        <div class="hint">Lihat semua riwayat</div>
    </a>

    <a href="{{ route('bookings.index', ['filter'=>'active']) }}"
       class="group dashboard-card">
        <div class="label">Aktif</div>
        <div class="value">{{ $bookingStats['active'] }}</div>
        <div class="hint">Sedang berjalan</div>
    </a>

    <a href="{{ route('bookings.index', ['filter'=>'completed']) }}"
       class="group dashboard-card">
        <div class="label">Selesai</div>
        <div class="value">{{ $bookingStats['completed'] }}</div>
        <div class="hint">Riwayat selesai</div>
    </a>
</div>

<div class="mt-10 grid lg:grid-cols-[1.3fr_0.7fr] gap-10">

{{-- MAIN --}}
<div class="space-y-6">

<div class="panel">
    <div class="flex justify-between items-center">
        <h2 class="title">Booking Aktif</h2>
        <a href="{{ route('motorcycles.index') }}" class="btn-primary">+ Sewa</a>
    </div>

@if ($activeBooking)

<div class="mt-6 booking-card">

    <div class="flex justify-between">
        <div>
            <div class="code">{{ $activeBooking->booking_code }}</div>
            <div class="name">
                {{ $activeBooking->motorcycle->brand }} {{ $activeBooking->motorcycle->model }}
            </div>
            <div class="date">
                {{ $activeBooking->start_date->translatedFormat('d M Y') }}
                -
                {{ $activeBooking->end_date->translatedFormat('d M Y') }}
            </div>
        </div>

        <span class="badge {{ $activeBadgeClass }}">
            {{ $activeBooking->statusLabel() }}
        </span>
    </div>

    <div class="grid grid-cols-3 gap-4 mt-5">
        <div class="mini-card">
            <span>Durasi</span>
            <strong>{{ $activeBooking->duration_days }} hari</strong>
        </div>

        <div class="mini-card">
            <span>Total</span>
            <strong>Rp{{ number_format($activeBooking->total_price, 0, ',', '.') }}</strong>
        </div>

        <div class="mini-card">
            <span>Pembayaran</span>
            <strong>{{ $activeBooking->latestPayment?->statusLabel() ?? 'Belum' }}</strong>
        </div>
    </div>

    {{-- CONTEXT ACTION --}}
    <div class="mt-6 flex gap-3">
        <a href="{{ route('bookings.show', $activeBooking) }}"
           class="btn-light">Detail</a>

        @if ($activeBooking->canUploadPaymentProof())
            <a href="{{ route('payments.create', $activeBooking) }}"
               class="btn-primary">
               Bayar Sekarang
            </a>
        @endif
    </div>

</div>

@else

<div class="empty-state">
    <p>Tidak ada booking aktif</p>
    <a href="{{ route('motorcycles.index') }}" class="btn-primary mt-4">
        Mulai Sewa
    </a>
</div>

@endif
</div>

{{-- HISTORY --}}
<div class="panel">
    <h2 class="title">Riwayat</h2>

    <div class="mt-5 space-y-3">
        @forelse ($latestBookings as $booking)
            <a href="{{ route('bookings.show', $booking) }}"
               class="history-item">

                <div>
                    <div class="code">{{ $booking->booking_code }}</div>
                    <div class="name">
                        {{ $booking->motorcycle->brand }} {{ $booking->motorcycle->model }}
                    </div>
                </div>

                <div class="price">
                    Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                </div>

            </a>
        @empty
            <div class="empty-small">Belum ada riwayat</div>
        @endforelse
    </div>
</div>

</div>

{{-- SIDEBAR --}}
<aside class="space-y-6">

<div class="panel">
    <h3 class="title-sm">Profil</h3>

    <div class="space-y-3 mt-4">
        <div class="row"><span>Telepon</span><strong>{{ $user->phone_number ?: '-' }}</strong></div>
        <div class="row"><span>Paspor</span><strong>{{ filled($user->passport_number) ? 'Ada' : 'Belum' }}</strong></div>
        <div class="row"><span>SIM</span><strong>{{ $user->has_license ? 'Ada' : 'Tidak' }}</strong></div>
    </div>

    <a href="{{ route('profile.edit') }}" class="btn-light mt-5 w-full text-center">
        Edit Profil
    </a>
</div>

<div class="panel">
    <h3 class="title-sm">Safety</h3>
    <p class="text-sm text-bali-muted mt-3">
        {{ $user->hasTrustedRiderBadge()
            ? 'Status Trusted Rider aktif.'
            : 'Selesaikan rental aman untuk mendapatkan badge.' }}
    </p>
</div>

</aside>

</div>
</div>
</section>

{{-- STYLE --}}
<style>
.dashboard-card{
    @apply block p-6 rounded-2xl bg-white shadow transition;
}
.dashboard-card:hover{
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
.label{ @apply text-sm text-bali-muted; }
.value{ @apply text-4xl font-black mt-2; }
.hint{ @apply text-xs mt-2 text-bali-muted; }

.panel{
    @apply p-7 bg-white rounded-3xl shadow;
}
.title{ @apply text-xl font-black; }
.title-sm{ @apply text-lg font-black; }

.booking-card{
    @apply p-6 rounded-2xl bg-gradient-to-br from-white to-slate-50 shadow-inner;
}
.code{ @apply text-xs text-bali-teal font-bold; }
.name{ @apply font-semibold mt-1; }
.date{ @apply text-sm text-bali-muted mt-1; }

.mini-card{
    @apply p-3 bg-white rounded-xl shadow-sm text-sm;
}

.history-item{
    @apply flex justify-between items-center p-4 rounded-xl bg-slate-50 hover:bg-white hover:shadow transition;
}

.empty-state{
    @apply text-center py-10 text-bali-muted;
}
.empty-small{
    @apply text-center text-bali-muted py-4;
}

.row{
    @apply flex justify-between text-sm;
}
</style>

@endsection