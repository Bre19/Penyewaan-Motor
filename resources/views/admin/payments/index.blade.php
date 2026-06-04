@extends('layouts.admin')

@section('page-title', 'Verifikasi Pembayaran')

@section('content')
<div class="rounded-[2rem] border border-bali-line bg-white p-6 shadow-xl">
    <form method="GET" action="{{ route('admin.payments.index') }}" class="grid gap-4 lg:grid-cols-[1fr_260px_auto_auto] lg:items-end">
        <div>
            <label for="search" class="mb-2 block text-sm font-black text-bali-navy">Pencarian</label>
            <input
                id="search"
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Kode pembayaran, booking, penyewa, motor, atau plat"
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

        <a href="{{ route('admin.payments.index') }}"
           class="rounded-2xl bg-slate-100 px-6 py-4 text-center text-sm font-black text-bali-navy transition hover:bg-slate-200">
            Reset
        </a>
    </form>
</div>

<div class="mt-8 rounded-[2rem] border border-bali-line bg-white shadow-xl">
    <div class="border-b border-bali-line p-6">
        <h2 class="text-2xl font-black text-bali-navy">Daftar Pembayaran</h2>
        <p class="mt-2 text-sm text-bali-muted">Verifikasi bukti pembayaran dari penyewa.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[980px] text-left text-sm">
            <thead class="bg-slate-100 text-xs uppercase tracking-wide text-bali-muted">
                <tr>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Booking</th>
                    <th class="px-6 py-4">Penyewa</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4">Nominal</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-bali-line">
                @forelse ($payments as $payment)
                    <tr class="align-top transition hover:bg-slate-50">
                        <td class="px-6 py-5">
                            <strong class="text-bali-navy">{{ $payment->payment_code }}</strong>
                            <span class="mt-1 block text-xs text-bali-muted">
                                {{ $payment->uploaded_at?->translatedFormat('d M Y H:i') ?? '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <strong class="text-bali-navy">{{ $payment->booking->booking_code }}</strong>
                            <span class="mt-1 block text-xs text-bali-muted">
                                {{ $payment->booking->motorcycle->brand }} {{ $payment->booking->motorcycle->model }}
                            </span>
                        </td>

                        <td class="px-6 py-5">
                            <strong class="text-bali-navy">{{ $payment->user->name }}</strong>
                            <span class="mt-1 block text-xs text-bali-muted">{{ $payment->user->email }}</span>
                        </td>

                        <td class="px-6 py-5 text-bali-muted">
                            {{ $payment->methodLabel() }}
                        </td>

                        <td class="px-6 py-5">
                            <strong class="text-bali-navy">
                                Rp{{ number_format($payment->amount, 0, ',', '.') }}
                            </strong>
                        </td>

                        <td class="px-6 py-5">
                            <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-black text-bali-navy">
                                {{ $payment->statusLabel() }}
                            </span>
                        </td>

                        <td class="px-6 py-5 text-right">
                            <a href="{{ route('admin.payments.show', $payment) }}"
                               class="inline-flex rounded-full bg-bali-navy px-5 py-2 text-sm font-black text-white transition hover:bg-bali-slate">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-bali-muted">
                            Pembayaran tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-bali-line p-6">
        {{ $payments->links() }}
    </div>
</div>
@endsection