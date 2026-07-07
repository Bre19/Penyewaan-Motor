@extends('layouts.admin')

@section('page-title', 'Verifikasi Booking')

@section('content')
<div class="rounded-[2rem] border border-bali-line bg-white p-6 shadow-xl">
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="grid gap-4 lg:grid-cols-[1fr_260px_auto_auto] lg:items-end">
        <div>
            <label for="search" class="mb-2 block text-sm font-black text-bali-navy">Pencarian</label>
            <input
                id="search"
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Kode booking, nama penyewa, email, jenis motor, kode unit, atau plat nomor"
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
            >
        </div>

        <div>
            <label for="status" class="mb-2 block text-sm font-black text-bali-navy">Status</label>
            <select
                id="status"
                name="status"
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
            >
                <option value="">Semua Status</option>
                @foreach ($statusLabels as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="rounded-2xl bg-bali-navy px-6 py-4 text-sm font-black text-white transition hover:bg-bali-slate">
            Filter
        </button>

        <a href="{{ route('admin.bookings.index') }}"
           class="rounded-2xl bg-slate-100 px-6 py-4 text-center text-sm font-black text-bali-navy transition hover:bg-slate-200">
            Reset
        </a>
    </form>
</div>

<div class="mt-8 rounded-[2rem] border border-bali-line bg-white shadow-xl">
    <div class="border-b border-bali-line p-6">
        <h2 class="text-2xl font-black text-bali-navy">Daftar Booking</h2>
        <p class="mt-2 text-sm text-bali-muted">Verifikasi pengajuan penyewaan motor dari penyewa.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[980px] text-left text-sm">
            <thead class="bg-slate-100 text-xs uppercase tracking-wide text-bali-muted">
                <tr>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Penyewa</th>
                    <th class="px-6 py-4">Motor</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-bali-line">
                @forelse ($bookings as $booking)
                    <tr class="align-top transition hover:bg-slate-50">
                        <td class="px-6 py-5">
                            <strong class="text-bali-navy">{{ $booking->booking_code }}</strong>
                            <span class="mt-1 block text-xs text-bali-muted">
                                {{ $booking->created_at->translatedFormat('d M Y H:i') }}
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <strong class="text-bali-navy">{{ $booking->user->name }}</strong>
                            <span class="mt-1 block text-xs text-bali-muted">{{ $booking->user->email }}</span>
                        </td>

                        <td class="px-6 py-5">

                            @php
                                $unit = $booking->rentedMotorcycle();
                            @endphp

                            @if($unit)

                                <strong class="text-bali-navy">
                                    {{ $unit->brand }} {{ $unit->model }}
                                </strong>

                            @else

                                <strong class="text-red-600">
                                    Unit tidak ditemukan
                                </strong>

                            @endif

                            @if($booking->motorcycleStock)

                                <div class="mt-3 space-y-1">

                                    <div class="text-xs text-slate-500">
                                        Unit
                                    </div>

                                    <div class="font-semibold text-bali-ink">
                                        {{ $booking->motorcycleStock->stock_code }}
                                    </div>

                                    <div class="text-xs text-slate-500">
                                        Plat
                                    </div>

                                    <div class="font-semibold text-bali-ink">
                                        {{ $booking->motorcycleStock->plate_number }}
                                    </div>

                                </div>

                            @endif

                        </td>

                        <td class="px-6 py-5 text-bali-muted">
                            {{ $booking->start_date->translatedFormat('d M Y') }}
                            <span class="block">s/d {{ $booking->end_date->translatedFormat('d M Y') }}</span>
                        </td>

                        <td class="px-6 py-5">
                            <strong class="text-bali-navy">
                                Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                            </strong>
                            <span class="mt-1 block text-xs text-bali-muted">{{ $booking->duration_days }} hari</span>
                        </td>

                        <td class="px-6 py-5">
                            @php

                            $statusClass = match($booking->status){

                                \App\Models\Booking::STATUS_PENDING_APPROVAL
                                    => 'bg-yellow-100 text-yellow-700',

                                \App\Models\Booking::STATUS_WAITING_PAYMENT,
                                \App\Models\Booking::STATUS_WAITING_PAYMENT_VERIFICATION
                                    => 'bg-orange-100 text-orange-700',

                                \App\Models\Booking::STATUS_PAYMENT_CONFIRMED,
                                \App\Models\Booking::STATUS_READY_TO_DELIVER
                                    => 'bg-blue-100 text-blue-700',

                                \App\Models\Booking::STATUS_ONGOING
                                    => 'bg-emerald-100 text-emerald-700',

                                \App\Models\Booking::STATUS_COMPLETED
                                    => 'bg-teal-100 text-teal-700',

                                \App\Models\Booking::STATUS_CANCELLED,
                                \App\Models\Booking::STATUS_REJECTED
                                    => 'bg-red-100 text-red-700',

                                default
                                    => 'bg-slate-100 text-slate-700',

                            };

                            @endphp

                            <span class="rounded-full px-4 py-2 text-xs font-black {{ $statusClass }}">
                                {{ $booking->statusLabel() }}
                            </span>
                        </td>

                        <td class="px-6 py-5 text-right">
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                               class="inline-flex rounded-full bg-bali-navy px-5 py-2 text-sm font-black text-white transition hover:bg-bali-slate">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-bali-muted">
                            Booking tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-bali-line p-6">
        {{ $bookings->links() }}
    </div>
</div>
@endsection