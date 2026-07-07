@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Menunggu Verifikasi</span>
        <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $stats['pending_bookings'] }}</strong>
    </div>

    <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Booking Aktif</span>
        <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $stats['active_bookings'] }}</strong>
    </div>

    <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Sewa Selesai</span>
        <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $stats['completed_bookings'] }}</strong>
    </div>

    <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Penyewa</span>
        <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $stats['customers'] }}</strong>
    </div>
</div>

<div class="mt-6 grid gap-6 md:grid-cols-3">
    <div class="rounded-[1.7rem] border border-teal-200 bg-teal-50 p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Unit Tersedia</span>
        <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $stats['available_motorcycles'] }}</strong>
    </div>

    <div class="rounded-[1.7rem] border border-amber-200 bg-amber-50 p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Unit Maintenance</span>
        <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $stats['maintenance_motorcycles'] }}</strong>
    </div>

    <div class="rounded-[1.7rem] border border-slate-200 bg-slate-100 p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Unit Disewa</span>
        <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $stats['unavailable_motorcycles'] }}</strong>
    </div>
</div>

<div class="mt-8 rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-black text-bali-navy">Booking Terbaru</h2>
            <p class="mt-2 text-sm text-bali-muted">Daftar pengajuan terbaru dari penyewa.</p>
        </div>

        <a href="{{ route('admin.bookings.index') }}"
           class="rounded-full bg-bali-orange px-6 py-3 text-center text-sm font-black text-white transition hover:bg-bali-orange-dark">
            Lihat Semua
        </a>
    </div>

    @php
        $statusColors = [
            \App\Models\Booking::STATUS_PENDING_APPROVAL => 'bg-amber-100 text-amber-700',
            \App\Models\Booking::STATUS_WAITING_PAYMENT => 'bg-orange-100 text-orange-700',
            \App\Models\Booking::STATUS_WAITING_PAYMENT_VERIFICATION => 'bg-purple-100 text-purple-700',
            \App\Models\Booking::STATUS_PAYMENT_CONFIRMED => 'bg-teal-100 text-teal-700',
            \App\Models\Booking::STATUS_READY_TO_DELIVER => 'bg-cyan-100 text-cyan-700',
            \App\Models\Booking::STATUS_ONGOING => 'bg-emerald-100 text-emerald-700',
            \App\Models\Booking::STATUS_COMPLETED => 'bg-slate-200 text-slate-700',
            \App\Models\Booking::STATUS_CANCELLED => 'bg-slate-200 text-slate-600',
            \App\Models\Booking::STATUS_REJECTED => 'bg-red-100 text-red-700',
        ];
    @endphp

    <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-bali-line">
        @forelse ($latestBookings as $booking)
            <div class="flex flex-col gap-4 border-b border-bali-line p-5 last:border-b-0 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="text-sm font-bold text-bali-muted">{{ $booking->booking_code }}</span>
                    <h3 class="mt-1 font-black text-bali-navy">
                        {{ $booking->rentedMotorcycle()?->brand }} {{ $booking->rentedMotorcycle()?->model }}
                    </h3>

                    @if($booking->motorcycleStock)
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-navy">
                            {{ $booking->motorcycleStock->stock_code }}
                        </span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-bali-muted">
                            {{ $booking->motorcycleStock->plate_number }}
                        </span>
                    </div>
                    @endif

                    <p class="mt-1 text-sm text-bali-muted">
                        {{ $booking->user->name }} • {{ $booking->start_date->translatedFormat('d M Y') }} - {{ $booking->end_date->translatedFormat('d M Y') }}
                    </p>

                    @if($booking->motorcycleStock)
                    <div class="mt-3 text-sm text-bali-muted">
                        Status Unit :
                        <strong class="text-bali-navy">
                            {{ $booking->motorcycleStock->statusLabel() }}
                        </strong>
                    </div>
                    @endif
                </div>

                <div class="flex flex-col gap-3 lg:items-end">
                    <span
                        class="rounded-full px-4 py-2 text-xs font-black
                        {{ $statusColors[$booking->status] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ $booking->statusLabel() }}
                    </span>
                    <a href="{{ route('admin.bookings.show', $booking) }}"
                       class="rounded-full bg-bali-navy px-5 py-2 text-center text-sm font-black text-white transition hover:bg-bali-slate">
                        Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-bali-muted">
                Belum ada booking.
            </div>
        @endforelse
    </div>
</div>
@endsection