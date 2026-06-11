@extends('layouts.admin')

@section('page-title', 'Selesaikan Rental')

@section('content')
<div class="grid gap-8 xl:grid-cols-[1fr_380px]">
    <section class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
        <span class="badge-teal">Safety Evaluation</span>

        <h2 class="mt-4 text-3xl font-black text-bali-navy">
            Evaluasi keamanan penyewa
        </h2>

        <p class="mt-3 max-w-3xl leading-8 text-bali-muted">
            Isi evaluasi setelah motor dikembalikan. Sistem akan menghitung Safety Score dan menentukan apakah penyewa mendapat badge Trusted Rider.
        </p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
                <strong class="block font-black">Input belum sesuai.</strong>
                <span class="mt-1 block">Periksa kembali evaluasi rental.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.bookings.storeCompletion', $booking) }}" class="mt-8 space-y-8">
            @csrf
            @method('PATCH')

            <div class="grid gap-4">
                <label class="flex items-start gap-3 rounded-2xl border border-teal-200 bg-teal-50 p-5 text-sm font-bold text-bali-navy">
                    <input
                        type="checkbox"
                        name="no_violation_report"
                        value="1"
                        checked
                        class="mt-1 h-4 w-4 rounded border-bali-line text-bali-teal focus:ring-bali-teal"
                    >
                    <span>
                        Tidak ada laporan pelanggaran selama masa sewa.
                    </span>
                </label>

                <label class="flex items-start gap-3 rounded-2xl border border-bali-line bg-slate-50 p-5 text-sm font-bold text-bali-navy">
                    <input
                        type="checkbox"
                        name="negligent_damage"
                        value="1"
                        class="mt-1 h-4 w-4 rounded border-bali-line text-bali-teal focus:ring-bali-teal"
                    >
                    <span>
                        Terdapat kerusakan akibat kelalaian penyewa.
                    </span>
                </label>

                <label class="flex items-start gap-3 rounded-2xl border border-bali-line bg-slate-50 p-5 text-sm font-bold text-bali-navy">
                    <input
                        type="checkbox"
                        name="reckless_report"
                        value="1"
                        class="mt-1 h-4 w-4 rounded border-bali-line text-bali-teal focus:ring-bali-teal"
                    >
                    <span>
                        Penyewa dilaporkan berkendara ugal-ugalan.
                    </span>
                </label>
            </div>

            <div class="rounded-[1.5rem] border border-bali-line bg-slate-100 p-5">
                <strong class="block text-bali-navy">Rumus Safety Score</strong>
                <ul class="mt-3 list-disc space-y-2 pl-5 text-sm leading-7 text-bali-muted">
                    <li>Score awal: 100</li>
                    <li>Ada laporan pelanggaran: -20</li>
                    <li>Kerusakan akibat kelalaian: -30</li>
                    <li>Dilaporkan ugal-ugalan: -40</li>
                    <li>Badge Trusted Rider diberikan jika score minimal 80</li>
                </ul>
            </div>

            <div>
                <label for="notes" class="mb-2 block text-sm font-black text-bali-navy">
                    Catatan evaluasi
                </label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="5"
                    class="textarea-ui"
                    placeholder="Contoh: motor kembali normal, tidak ada laporan pelanggaran"
                >{{ old('notes') }}</textarea>

                @error('notes')
                    <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3 border-t border-bali-line pt-6 sm:flex-row">
                <button type="submit" class="btn-primary">
                    Hitung Safety Score dan Selesaikan Rental
                </button>

                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-light">
                    Kembali
                </a>
            </div>
        </form>
    </section>

    <aside class="h-fit rounded-[2rem] border border-bali-line bg-white p-6 shadow-xl">
        <h3 class="text-2xl font-black text-bali-navy">Ringkasan Rental</h3>

        <div class="mt-5 grid gap-3 text-sm">
            <div class="rounded-2xl bg-slate-100 p-4">
                <span class="block text-bali-muted">Kode Booking</span>
                <strong class="mt-1 block text-bali-navy">{{ $booking->booking_code }}</strong>
            </div>

            <div class="rounded-2xl bg-slate-100 p-4">
                <span class="block text-bali-muted">Penyewa</span>
                <strong class="mt-1 block text-bali-navy">{{ $booking->user->name }}</strong>
            </div>

            <div class="rounded-2xl bg-slate-100 p-4">
                <span class="block text-bali-muted">Motor</span>
                <strong class="mt-1 block text-bali-navy">
                    {{ $booking->motorcycle->brand }} {{ $booking->motorcycle->model }}
                </strong>
            </div>

            <div class="rounded-2xl bg-slate-100 p-4">
                <span class="block text-bali-muted">Status Sekarang</span>
                <strong class="mt-1 block text-bali-teal-dark">{{ $booking->statusLabel() }}</strong>
            </div>
        </div>
    </aside>
</div>
@endsection