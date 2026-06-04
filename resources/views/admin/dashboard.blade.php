@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-5">
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
        <span class="text-sm font-bold text-bali-muted">Motor Tersedia</span>
        <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $stats['available_motorcycles'] }}</strong>
    </div>

    <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Penyewa</span>
        <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $stats['customers'] }}</strong>
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

    <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-bali-line">
        @forelse ($latestBookings as $booking)
            <div class="flex flex-col gap-4 border-b border-bali-line p-5 last:border-b-0 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <span class="text-sm font-bold text-bali-muted">{{ $booking->booking_code }}</span>
                    <h3 class="mt-1 font-black text-bali-navy">
                        {{ $booking->motorcycle->brand }} {{ $booking->motorcycle->model }}
                    </h3>
                    <p class="mt-1 text-sm text-bali-muted">
                        {{ $booking->user->name }} • {{ $booking->start_date->translatedFormat('d M Y') }} - {{ $booking->end_date->translatedFormat('d M Y') }}
                    </p>
                </div>

                <div class="flex flex-col gap-3 lg:items-end">
                    <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-black text-bali-navy">
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