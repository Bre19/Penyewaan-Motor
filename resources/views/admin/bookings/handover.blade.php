@extends('layouts.admin')

@section('page-title', 'Checklist Serah Terima')

@section('content')
<div class="grid gap-8 xl:grid-cols-[1fr_380px]">
    <section class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
        <span class="badge-orange">Serah Terima Motor</span>

        <h2 class="mt-4 text-3xl font-black text-bali-navy">
            Checklist serah-terima motor
        </h2>

        <p class="mt-3 max-w-3xl leading-8 text-bali-muted">
            Pastikan seluruh komponen aman dan lengkap sebelum motor diberikan kepada penyewa.
            Kalau checklist ini diisi asal-asalan, sistem memang tidak bisa marah, tapi kerusakan nanti tetap nyata.
        </p>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
                <strong class="block font-black">Checklist belum lengkap.</strong>
                <span class="mt-1 block">Semua item wajib dicentang dan foto wajib diupload.</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.bookings.storeHandover', $booking) }}" enctype="multipart/form-data" class="mt-8 space-y-8">
            @csrf
            @method('PATCH')

            <div class="grid gap-4 md:grid-cols-2">
                @foreach ([
                    'helmet_available' => 'Helm tersedia',
                    'brakes_normal' => 'Rem depan/belakang normal',
                    'headlight_normal' => 'Lampu utama normal',
                    'brake_light_normal' => 'Lampu rem normal',
                    'turn_signals_normal' => 'Sein normal',
                    'tires_proper' => 'Ban layak',
                    'mirrors_complete' => 'Spion lengkap',
                    'stnk_available' => 'STNK tersedia',
                ] as $name => $label)
                    <label class="flex items-start gap-3 rounded-2xl border border-bali-line bg-slate-50 p-4 text-sm font-bold text-bali-navy">
                        <input type="checkbox" name="{{ $name }}" value="1" required class="mt-1 h-4 w-4 rounded border-bali-line text-bali-teal focus:ring-bali-teal">
                        <span>{{ $label }}</span>
                    </label>

                    @error($name)
                        <p class="text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                @endforeach
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="motorcycle_condition_photo" class="mb-2 block text-sm font-black text-bali-navy">
                        Foto kondisi motor sebelum disewa
                    </label>
                    <input
                        id="motorcycle_condition_photo"
                        type="file"
                        name="motorcycle_condition_photo"
                        accept="image/*"
                        required
                        class="input-ui h-auto py-3"
                    >
                    @error('motorcycle_condition_photo')
                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_with_helmet_photo" class="mb-2 block text-sm font-black text-bali-navy">
                        Foto penyewa menerima motor memakai helm
                    </label>
                    <input
                        id="customer_with_helmet_photo"
                        type="file"
                        name="customer_with_helmet_photo"
                        accept="image/*"
                        required
                        class="input-ui h-auto py-3"
                    >
                    @error('customer_with_helmet_photo')
                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="notes" class="mb-2 block text-sm font-black text-bali-navy">
                    Catatan tambahan
                </label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="4"
                    class="textarea-ui"
                    placeholder="Contoh: kondisi body baik, bensin setengah, helm 2 unit"
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3 border-t border-bali-line pt-6 sm:flex-row">
                <button type="submit" class="btn-primary">
                    Simpan Checklist dan Mulai Rental
                </button>

                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-light">
                    Kembali
                </a>
            </div>
        </form>
    </section>

    <aside class="h-fit rounded-[2rem] border border-bali-line bg-white p-6 shadow-xl">
        <h3 class="text-2xl font-black text-bali-navy">Ringkasan Booking</h3>

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
                <span class="block text-bali-muted">Plat</span>
                <strong class="mt-1 block text-bali-navy">{{ $booking->motorcycle->plate_number }}</strong>
            </div>
        </div>
    </aside>
</div>
@endsection