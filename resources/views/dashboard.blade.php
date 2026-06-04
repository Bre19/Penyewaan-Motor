@extends('layouts.public')

@section('content')
@php
    $activeBadgeClass = $activeBooking
        ? match ($activeBooking->status) {
            \App\Models\Booking::STATUS_PENDING_APPROVAL => 'bg-amber-50 text-amber-700 border-amber-200',
            \App\Models\Booking::STATUS_APPROVED => 'bg-blue-50 text-blue-700 border-blue-200',
            \App\Models\Booking::STATUS_WAITING_PAYMENT => 'bg-orange-50 text-orange-700 border-orange-200',
            \App\Models\Booking::STATUS_WAITING_PAYMENT_VERIFICATION => 'bg-purple-50 text-purple-700 border-purple-200',
            \App\Models\Booking::STATUS_PAYMENT_CONFIRMED => 'bg-teal-50 text-teal-700 border-teal-200',
            \App\Models\Booking::STATUS_READY_TO_DELIVER => 'bg-cyan-50 text-cyan-700 border-cyan-200',
            \App\Models\Booking::STATUS_ONGOING => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        }
        : 'bg-slate-100 text-slate-700 border-slate-200';
@endphp

<section class="bg-gradient-to-br from-bali-navy via-slate-900 to-blue-950 py-16 text-white">
    <div class="mx-auto grid w-[min(1180px,92%)] gap-8 lg:grid-cols-[1fr_0.75fr] lg:items-end">
        <div>
            <span class="mb-3 inline-flex text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
                Dashboard Penyewa
            </span>
            <h1 class="text-4xl font-black tracking-[-0.04em]">
                Selamat Datang, {{ $user->name }}
            </h1>
            <p class="mt-4 max-w-2xl leading-8 text-slate-300">
                Pantau pengajuan sewa, status pembayaran, dan progres penyewaan motor Anda.
            </p>
        </div>

        <div class="rounded-[1.7rem] border border-white/10 bg-white/10 p-6 backdrop-blur">
            <span class="text-sm font-bold text-slate-300">Status akun</span>
            <strong class="mt-2 block text-2xl font-black">
                {{ $profileCompleted ? 'Data Awal Lengkap' : 'Data Belum Lengkap' }}
            </strong>
            <p class="mt-2 text-sm leading-6 text-slate-300">
                Dokumen upload seperti paspor, visa, SIM, dan tanda tangan digital akan dilanjutkan pada tahap berikutnya.
            </p>
        </div>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto w-[min(1180px,92%)]">
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

        <div class="grid gap-6 md:grid-cols-3">
            <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
                <span class="text-sm font-bold text-bali-muted">Total Pengajuan</span>
                <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $bookingStats['total'] }}</strong>
                <p class="mt-3 text-sm leading-6 text-bali-muted">Seluruh pengajuan sewa yang pernah dibuat.</p>
            </div>

            <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
                <span class="text-sm font-bold text-bali-muted">Pengajuan Aktif</span>
                <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $bookingStats['active'] }}</strong>
                <p class="mt-3 text-sm leading-6 text-bali-muted">Pengajuan yang masih berjalan atau menunggu proses admin.</p>
            </div>

            <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
                <span class="text-sm font-bold text-bali-muted">Sewa Selesai</span>
                <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $bookingStats['completed'] }}</strong>
                <p class="mt-3 text-sm leading-6 text-bali-muted">Penyewaan yang telah selesai.</p>
            </div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_0.75fr]">
            <div class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-2xl font-black text-bali-navy">Booking Aktif</h2>
                        <p class="mt-2 text-sm text-bali-muted">
                            Status pengajuan terbaru yang masih perlu dipantau.
                        </p>
                    </div>

                    <a href="{{ route('motorcycles.index') }}"
                       class="rounded-full bg-bali-orange px-6 py-3 text-center text-sm font-black text-white transition hover:bg-bali-orange-dark">
                        Cari Motor
                    </a>
                </div>

                @if ($activeBooking)
                    <div class="mt-8 rounded-[1.7rem] border border-bali-line p-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div>
                                <span class="text-sm font-bold text-bali-muted">{{ $activeBooking->booking_code }}</span>
                                <h3 class="mt-2 text-2xl font-black text-bali-navy">
                                    {{ $activeBooking->motorcycle->brand }} {{ $activeBooking->motorcycle->model }}
                                </h3>
                                <p class="mt-2 text-sm text-bali-muted">
                                    {{ $activeBooking->start_date->translatedFormat('d F Y') }}
                                    -
                                    {{ $activeBooking->end_date->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            <span class="w-fit rounded-full border px-4 py-2 text-xs font-black {{ $activeBadgeClass }}">
                                {{ $activeBooking->statusLabel() }}
                            </span>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-3">
                            <div class="rounded-2xl bg-slate-100 p-4">
                                <span class="block text-sm text-bali-muted">Durasi</span>
                                <strong class="mt-1 block text-bali-navy">{{ $activeBooking->duration_days }} hari</strong>
                            </div>

                            <div class="rounded-2xl bg-slate-100 p-4">
                                <span class="block text-sm text-bali-muted">Total</span>
                                <strong class="mt-1 block text-bali-navy">
                                    Rp{{ number_format($activeBooking->total_price, 0, ',', '.') }}
                                </strong>
                            </div>

                            <div class="rounded-2xl bg-slate-100 p-4">
                                <span class="block text-sm text-bali-muted">Lokasi</span>
                                <strong class="mt-1 block text-bali-navy">{{ $activeBooking->delivery_location }}</strong>
                            </div>
                        </div>

                        <a href="{{ route('bookings.show', $activeBooking) }}"
                           class="mt-6 inline-flex rounded-full bg-bali-navy px-6 py-3 text-sm font-black text-white transition hover:bg-bali-slate">
                            Lihat Detail
                        </a>
                    </div>
                @else
                    <div class="mt-8 rounded-[1.7rem] border border-dashed border-bali-line p-8 text-center">
                        <h3 class="text-xl font-black text-bali-navy">Belum ada booking aktif</h3>
                        <p class="mt-2 text-bali-muted">
                            Pilih motor dari katalog lalu ajukan penyewaan.
                        </p>
                    </div>
                @endif
            </div>

            <aside class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
                <h2 class="text-2xl font-black text-bali-navy">Status Dokumen</h2>
                <p class="mt-2 text-sm leading-7 text-bali-muted">
                    Data awal penyewa sudah dipakai untuk proses booking. Upload dokumen lengkap akan masuk tahap berikutnya.
                </p>

                <div class="mt-6 grid gap-3">
                    <div class="flex items-center justify-between rounded-2xl bg-slate-100 p-4">
                        <span class="text-sm font-bold text-bali-muted">Nomor Paspor</span>
                        <strong class="text-sm text-bali-navy">{{ filled($user->passport_number) ? 'Ada' : 'Belum Ada' }}</strong>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-slate-100 p-4">
                        <span class="text-sm font-bold text-bali-muted">Negara Asal</span>
                        <strong class="text-sm text-bali-navy">{{ filled($user->origin_country) ? $user->origin_country : 'Belum Ada' }}</strong>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-slate-100 p-4">
                        <span class="text-sm font-bold text-bali-muted">SIM</span>
                        <strong class="text-sm text-bali-navy">{{ $user->has_license ? 'Ada' : 'Tidak Ada' }}</strong>
                    </div>
                </div>
            </aside>
        </div>

        <div class="mt-8 rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
            <h2 class="text-2xl font-black text-bali-navy">Riwayat Booking</h2>

            <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-bali-line">
                @forelse ($latestBookings as $booking)
                    <div class="flex flex-col gap-4 border-b border-bali-line p-5 last:border-b-0 md:flex-row md:items-center md:justify-between">
                        <div>
                            <span class="text-sm font-bold text-bali-muted">{{ $booking->booking_code }}</span>
                            <h3 class="mt-1 font-black text-bali-navy">
                                {{ $booking->motorcycle->brand }} {{ $booking->motorcycle->model }}
                            </h3>
                            <p class="mt-1 text-sm text-bali-muted">
                                {{ $booking->start_date->translatedFormat('d M Y') }}
                                -
                                {{ $booking->end_date->translatedFormat('d M Y') }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-3 md:items-end">
                            <strong class="text-bali-navy">
                                Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                            </strong>
                            <a href="{{ route('bookings.show', $booking) }}"
                               class="rounded-full bg-slate-100 px-5 py-2 text-center text-sm font-black text-bali-navy transition hover:bg-slate-200">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-bali-muted">
                        Belum ada riwayat booking.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection