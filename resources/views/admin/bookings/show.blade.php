@extends('layouts.admin')

@php
    use App\Models\Booking;
@endphp

@section('page-title', 'Detail Booking')

@section('content')
@if (session('success'))
    <div class="mb-8 rounded-2xl border border-teal-200 bg-teal-50 p-5 text-sm font-semibold text-bali-teal-dark">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm font-semibold text-red-700">{{ session('error') }}</div>
@endif

<div class="grid gap-8 xl:grid-cols-[1fr_380px]">
    <section class="space-y-8">
        <div class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
            <div class="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                <div>
                    <span class="text-sm font-black uppercase tracking-wide text-bali-teal">{{ $booking->booking_code }}</span>
                    <h2 class="mt-2 text-3xl font-black text-bali-navy">{{ $booking->motorcycle->brand }} {{ $booking->motorcycle->model }}</h2>
                    <p class="mt-2 text-bali-muted">{{ $booking->motorcycle->type ?? '-' }} • {{ $booking->motorcycle->year ?? '-' }} • {{ $booking->motorcycle->plate_number }}</p>
                </div>
                <span class="w-fit rounded-full bg-slate-100 px-5 py-3 text-xs font-black text-bali-navy">{{ $booking->statusLabel() }}</span>
            </div>

            <div class="mt-8 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-bali-line p-5"><span class="block text-sm text-bali-muted">Penyewa</span><strong class="mt-1 block text-bali-navy">{{ $booking->user->name }}</strong><span class="mt-1 block text-sm text-bali-muted">{{ $booking->user->email }}</span></div>
                <div class="rounded-2xl border border-bali-line p-5"><span class="block text-sm text-bali-muted">Telepon / WhatsApp</span><strong class="mt-1 block text-bali-navy">{{ $booking->user->phone_number ?? '-' }}</strong></div>
                <div class="rounded-2xl border border-bali-line p-5"><span class="block text-sm text-bali-muted">Tanggal Mulai</span><strong class="mt-1 block text-bali-navy">{{ $booking->start_date->translatedFormat('d F Y') }}</strong></div>
                <div class="rounded-2xl border border-bali-line p-5"><span class="block text-sm text-bali-muted">Tanggal Selesai</span><strong class="mt-1 block text-bali-navy">{{ $booking->end_date->translatedFormat('d F Y') }}</strong></div>
                <div class="rounded-2xl border border-bali-line p-5"><span class="block text-sm text-bali-muted">Durasi</span><strong class="mt-1 block text-bali-navy">{{ $booking->duration_days }} hari</strong></div>
                <div class="rounded-2xl border border-bali-line p-5"><span class="block text-sm text-bali-muted">Lokasi Pengantaran</span><strong class="mt-1 block text-bali-navy">{{ $booking->delivery_location }}</strong></div>
            </div>

            <div class="mt-8 rounded-[1.5rem] bg-slate-100 p-6">
                <span class="block text-sm font-bold text-bali-muted">Total Biaya</span>
                <strong class="mt-2 block text-3xl font-black text-bali-navy">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                <p class="mt-2 text-sm text-bali-muted">Rp{{ number_format($booking->price_per_day, 0, ',', '.') }} × {{ $booking->duration_days }} hari</p>
            </div>
        </div>

        <div class="rounded-[2rem] border {{ $riskClass }} p-6 shadow-xl">
            <span class="text-xs font-black uppercase tracking-[0.18em]">Risk Scoring</span>
            <h3 class="mt-2 text-2xl font-black">{{ $riskLabel }}</h3>
            <p class="mt-2 text-sm leading-7">Penilaian otomatis berdasarkan riwayat penyewa dan hasil safety score sebelumnya.</p>
            <div class="mt-5 grid gap-2">
                @foreach ($riskReasons as $reason)
                    <div class="rounded-2xl bg-white/70 p-4 text-sm font-semibold">{{ $reason }}</div>
                @endforeach
            </div>
        </div>

        <div class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
            <h3 class="text-xl font-black text-bali-navy">Data Penyewa</h3>
            <div class="mt-4 grid gap-3 text-sm md:grid-cols-2">
                <div class="rounded-2xl bg-slate-100 p-4"><span class="block text-bali-muted">Alamat Saat Ini</span><strong class="mt-1 block text-bali-navy">{{ $booking->user->current_address ?? '-' }}</strong></div>
                <div class="rounded-2xl bg-slate-100 p-4"><span class="block text-bali-muted">Nomor Paspor</span><strong class="mt-1 block text-bali-navy">{{ $booking->user->passport_number ?? '-' }}</strong></div>
                <div class="rounded-2xl bg-slate-100 p-4"><span class="block text-bali-muted">Negara Asal</span><strong class="mt-1 block text-bali-navy">{{ $booking->user->origin_country ?? '-' }}</strong></div>
                <div class="rounded-2xl bg-slate-100 p-4"><span class="block text-bali-muted">SIM</span><strong class="mt-1 block text-bali-navy">{{ $booking->user->has_license ? 'Ada' : 'Tidak Ada' }}</strong></div>
            </div>
            <div class="mt-6"><h3 class="text-xl font-black text-bali-navy">Catatan Penyewa</h3><p class="mt-4 rounded-2xl border border-bali-line p-5 leading-8 text-bali-muted">{{ $booking->customer_note ?: 'Tidak ada catatan tambahan.' }}</p></div>
        </div>

        @if ($booking->rentalChecklist)
            <div class="rounded-[2rem] border border-teal-200 bg-teal-50 p-8 shadow-xl">
                <h3 class="text-xl font-black text-bali-navy">Checklist Serah Terima</h3>
                <p class="mt-2 text-sm leading-7 text-bali-muted">Checklist disimpan pada {{ $booking->rentalChecklist->checked_at?->translatedFormat('d F Y H:i') ?? '-' }}.</p>
                <div class="mt-5 grid gap-3 md:grid-cols-2">
                    <a href="{{ asset('storage/' . $booking->rentalChecklist->motorcycle_condition_photo) }}" target="_blank" class="rounded-2xl bg-white p-4 text-sm font-black text-bali-teal-dark">Lihat Foto Kondisi Motor</a>
                    <a href="{{ asset('storage/' . $booking->rentalChecklist->customer_with_helmet_photo) }}" target="_blank" class="rounded-2xl bg-white p-4 text-sm font-black text-bali-teal-dark">Lihat Foto Penyewa Memakai Helm</a>
                </div>
            </div>
        @endif

        @if ($booking->rentalSafetyScore)
            <div class="rounded-[2rem] border border-orange-200 bg-orange-50 p-8 shadow-xl">
                <h3 class="text-xl font-black text-bali-navy">Safety Score</h3>
                <div class="mt-5 grid gap-4 md:grid-cols-3">
                    <div class="rounded-2xl bg-white p-4"><span class="block text-sm text-bali-muted">Score</span><strong class="mt-1 block text-3xl font-black text-bali-navy">{{ $booking->rentalSafetyScore->score }}</strong></div>
                    <div class="rounded-2xl bg-white p-4"><span class="block text-sm text-bali-muted">Badge</span><strong class="mt-1 block text-bali-navy">{{ $booking->rentalSafetyScore->badge_awarded ? 'Trusted Rider' : 'Tidak Mendapat Badge' }}</strong></div>
                    <div class="rounded-2xl bg-white p-4"><span class="block text-sm text-bali-muted">Evaluasi</span><strong class="mt-1 block text-bali-navy">{{ $booking->rentalSafetyScore->evaluated_at?->translatedFormat('d M Y') ?? '-' }}</strong></div>
                </div>
            </div>
        @endif

        <div class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
            <h3 class="text-xl font-black text-bali-navy">Timeline Status Booking</h3>
            <div class="mt-5 grid gap-3">
                @forelse ($booking->statusHistories as $history)
                    <div class="rounded-2xl bg-slate-100 p-4">
                        <strong class="text-bali-navy">{{ Booking::statusLabels()[$history->status_to] ?? $history->status_to }}</strong>
                        <p class="mt-1 text-sm leading-6 text-bali-muted">{{ $history->note ?: 'Status diperbarui.' }}</p>
                        <span class="mt-2 block text-xs font-bold text-bali-muted">{{ $history->created_at->translatedFormat('d M Y H:i') }} • {{ $history->changedBy?->name ?? 'Sistem' }}</span>
                    </div>
                @empty
                    <div class="rounded-2xl bg-slate-100 p-4 text-sm font-semibold text-bali-muted">Belum ada riwayat status.</div>
                @endforelse
            </div>
        </div>
    </section>

    <aside class="h-fit rounded-[2rem] border border-bali-line bg-white p-6 shadow-xl">
        <h3 class="text-2xl font-black text-bali-navy">Aksi Admin</h3>
        <p class="mt-2 text-sm leading-7 text-bali-muted">Verifikasi pengajuan, lakukan serah-terima motor, dan selesaikan rental sesuai status booking.</p>

        @if ($booking->status === Booking::STATUS_PENDING_APPROVAL)
            <form method="POST" action="{{ route('admin.bookings.approve', $booking) }}" class="mt-6">@csrf @method('PATCH')<button type="submit" class="w-full rounded-full bg-bali-teal px-6 py-4 text-sm font-black text-white transition hover:bg-bali-teal-dark">Setujui Booking</button></form>
            <form method="POST" action="{{ route('admin.bookings.reject', $booking) }}" class="mt-5">@csrf @method('PATCH')<textarea name="rejection_reason" rows="5" required placeholder="Alasan penolakan" class="w-full rounded-2xl border border-bali-line px-4 py-3 text-sm outline-none"></textarea><button type="submit" class="mt-4 w-full rounded-full bg-red-50 px-6 py-4 text-sm font-black text-red-700 transition hover:bg-red-100">Tolak Booking</button></form>
        @elseif ($booking->status === Booking::STATUS_PAYMENT_CONFIRMED)
            <a href="{{ route('admin.bookings.handover', $booking) }}" class="mt-6 block rounded-full bg-bali-teal px-6 py-4 text-center text-sm font-black text-white transition hover:bg-bali-teal-dark">Isi Checklist Serah Terima</a>
        @elseif ($booking->status === Booking::STATUS_ONGOING)
            <a href="{{ route('admin.bookings.complete', $booking) }}" class="mt-6 block rounded-full bg-bali-orange px-6 py-4 text-center text-sm font-black text-white transition hover:bg-bali-orange-dark">Selesaikan Rental</a>
        @else
            <div class="mt-6 rounded-2xl bg-slate-100 p-5 text-sm leading-7 text-bali-muted">Tidak ada aksi admin lanjutan untuk status saat ini.</div>
        @endif

        <div class="mt-6 rounded-2xl bg-slate-100 p-5 text-sm leading-7 text-bali-muted"><strong class="block text-bali-navy">Terms Accepted</strong><span>{{ $booking->terms_accepted_at ? $booking->terms_accepted_at->translatedFormat('d F Y H:i') : 'Belum tercatat' }}</span></div>
        <a href="{{ route('admin.bookings.index') }}" class="mt-5 block rounded-full bg-bali-navy px-6 py-4 text-center text-sm font-black text-white transition hover:bg-bali-slate">Kembali ke Daftar</a>
    </aside>
</div>
@endsection
