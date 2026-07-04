@extends('layouts.public')

@section('content')
@php

$totalUnit = $motorcycle->stocks->count();

$availableUnit = $motorcycle->stocks
    ->where('status', 'available')
    ->count();

@endphp
<section class="relative overflow-hidden bg-bali-navy py-20 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(13,148,136,0.34),transparent_30rem),radial-gradient(circle_at_85%_5%,rgba(249,115,22,0.28),transparent_26rem)]"></div>

    <div class="container-page relative">
        <span class="badge-teal bg-white/10 text-teal-200">
            Pengajuan Sewa
        </span>
        <h1 class="mt-5 max-w-4xl text-5xl font-black leading-tight tracking-[-0.05em] md:text-6xl">
            Ajukan sewa {{ $motorcycle->brand }} {{ $motorcycle->model }}
        </h1>
        <p class="mt-5 max-w-2xl leading-8 text-slate-300">
            Isi detail tanggal, lokasi pengantaran, dan catatan tambahan untuk admin.
        </p>
    </div>
</section>

<section class="py-16">
    <div class="container-page grid gap-8 lg:grid-cols-[0.72fr_1fr]">
        <aside class="surface-card h-fit rounded-[2rem] p-6">
            <div class="relative flex h-72 items-center justify-center overflow-hidden rounded-[1.7rem] bg-gradient-to-br from-slate-100 via-white to-orange-50 p-6 text-center font-black text-bali-navy">
                @if($availableUnit > 0)

                <span class="absolute left-5 top-5 rounded-full bg-emerald-500 px-4 py-2 text-xs font-black text-white shadow-sm">

                    {{ $availableUnit }} Unit Ready

                </span>

                @else

                <span class="absolute left-5 top-5 rounded-full bg-red-500 px-4 py-2 text-xs font-black text-white shadow-sm">

                    Stok Habis

                </span>

                @endif

                @if ($motorcycle->image)
                    <img src="{{ asset('storage/' . $motorcycle->image) }}" alt="{{ $motorcycle->brand }} {{ $motorcycle->model }}" class="h-full w-full object-contain">
                @else
                    {{ $motorcycle->brand }} {{ $motorcycle->model }}
                @endif
            </div>

            <div class="mt-6">
                <h2 class="text-3xl font-black text-bali-navy">
                    {{ $motorcycle->brand }} {{ $motorcycle->model }}
                </h2>
                <p class="mt-2 font-semibold text-bali-muted">
                    {{ $motorcycle->type ?? '-' }} • {{ $motorcycle->year ?? '-' }}
                </p>
            </div>

            <div class="mt-6 rounded-[1.5rem] bg-bali-navy p-6 text-white">
                <span class="block text-sm font-bold text-slate-300">Harga per hari</span>
                <strong class="mt-2 block text-3xl font-black">
                    Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}
                </strong>
            </div>

            <div class="mt-6 grid gap-3 text-sm">

                <div class="flex justify-between rounded-2xl bg-slate-100 p-4">

                    <span class="font-bold text-bali-muted">

                        Total Unit

                    </span>

                    <strong class="text-bali-navy">

                        {{ $totalUnit }}

                    </strong>

                </div>

                <div class="flex justify-between rounded-2xl bg-emerald-50 p-4">

                    <span class="font-bold text-emerald-700">

                        Unit Tersedia

                    </span>

                    <strong class="text-emerald-700">

                        {{ $availableUnit }}

                    </strong>

                </div>

                <div class="flex justify-between rounded-2xl bg-slate-100 p-4">

                    <span class="font-bold text-bali-muted">

                        Status Booking

                    </span>

                    <strong class="text-bali-teal-dark">

                        Menunggu Persetujuan

                    </strong>

                </div>

            </div>
        </aside>

        <div class="surface-card rounded-[2rem] p-8">
            <div class="mb-8">
                <span class="badge-orange">Form Booking</span>
                <h2 class="mt-4 text-3xl font-black text-bali-navy">Detail pengajuan</h2>
                <p class="mt-2 text-sm leading-7 text-bali-muted">

                    Sistem akan memilih secara otomatis salah satu unit motor yang tersedia
                    sesuai tanggal penyewaan Anda. Nomor plat dan unit fisik akan ditentukan
                    setelah proses booking berhasil dibuat.

                </p>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5 text-sm text-red-700">
                    <strong class="block font-black">Input belum sesuai.</strong>
                    <span class="mt-1 block">Periksa kembali data yang ditandai.</span>
                </div>
            @endif

            <form id="bookingForm" method="POST" action="{{ route('bookings.store', $motorcycle) }}" class="space-y-6">
                @csrf
                <input type="hidden" id="termsAcceptedInput" name="terms_accepted" value="0">

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="start_date" class="mb-2 block text-sm font-black text-bali-navy">
                            Tanggal Mulai
                        </label>
                        <input id="start_date" type="date" name="start_date" value="{{ old('start_date', request('start_date')) }}" required class="input-ui">
                        @error('start_date')
                            <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="mb-2 block text-sm font-black text-bali-navy">
                            Tanggal Selesai
                        </label>
                        <input id="end_date" type="date" name="end_date" value="{{ old('end_date', request('end_date')) }}" required class="input-ui">
                        @error('end_date')
                            <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="delivery_location" class="mb-2 block text-sm font-black text-bali-navy">
                        Lokasi Pengantaran
                    </label>
                    <input
                        id="delivery_location"
                        type="text"
                        name="delivery_location"
                        value="{{ old('delivery_location', request('delivery_location')) }}"
                        required
                        placeholder="Contoh: Hotel area Kuta, Canggu, Ubud"
                        class="input-ui"
                    >
                    @error('delivery_location')
                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="customer_note" class="mb-2 block text-sm font-black text-bali-navy">
                        Catatan Tambahan
                    </label>
                    <textarea
                        id="customer_note"
                        name="customer_note"
                        rows="4"
                        placeholder="Contoh: pengantaran pagi, lokasi detail, atau permintaan tambahan"
                        class="textarea-ui"
                    >{{ old('customer_note') }}</textarea>
                    @error('customer_note')
                        <p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-[1.5rem] border border-teal-200 bg-teal-50 p-5">
                    <strong class="block text-bali-navy">Catatan sistem</strong>
                    <p class="mt-2 text-sm leading-7 text-bali-muted">
                        Total biaya dihitung otomatis berdasarkan jumlah hari sewa dan harga per hari.
                        Booking belum aktif sampai admin menyetujui pengajuan.
                    </p>
                </div>

                <div class="flex flex-col gap-3 border-t border-bali-line pt-6 sm:flex-row">
                    <button type="button" id="openTermsModal" class="btn-primary">
                        Kirim Pengajuan
                    </button>

                    <a href="{{ route('motorcycles.show', $motorcycle) }}" class="btn-light">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
<div id="termsModal" class="fixed inset-0 z-[999] hidden items-center justify-center bg-slate-950/70 px-4">
    <div class="max-h-[92vh] w-full max-w-3xl overflow-hidden rounded-[2rem] bg-white shadow-2xl">
        <div class="border-b border-bali-line p-6">
            <span class="badge-orange">Terms & Condition</span>
            <h2 class="mt-4 text-2xl font-black text-bali-navy">
                Aturan Penggunaan Motor Selama Masa Sewa
            </h2>
            <p class="mt-2 text-sm leading-7 text-bali-muted">
                Baca seluruh ketentuan sampai selesai sebelum menyetujui pengajuan sewa.
            </p>
        </div>

        <div id="termsContent" class="max-h-[420px] overflow-y-auto p-6 text-sm leading-8 text-bali-muted">
            <h3 class="mb-3 text-lg font-black text-bali-navy">1. Kewajiban Penyewa</h3>
            <p>
                Penyewa wajib menggunakan motor secara bertanggung jawab, mematuhi peraturan lalu lintas,
                menggunakan helm, membawa identitas yang diperlukan, dan menjaga kondisi motor selama masa sewa.
            </p>

            <h3 class="mb-3 mt-6 text-lg font-black text-bali-navy">2. Keselamatan Berkendara</h3>
            <ul class="list-disc space-y-2 pl-5">
                <li>Penyewa wajib menggunakan helm selama berkendara.</li>
                <li>Penyewa dilarang berkendara secara ugal-ugalan atau membahayakan pengguna jalan lain.</li>
                <li>Penyewa dilarang mengendarai motor dalam pengaruh alkohol, narkoba, atau kondisi tidak layak berkendara.</li>
                <li>Penyewa wajib mematuhi rambu lalu lintas, batas kecepatan, dan aturan jalan setempat.</li>
                <li>Penyewa dilarang membawa penumpang melebihi kapasitas motor.</li>
            </ul>

            <h3 class="mb-3 mt-6 text-lg font-black text-bali-navy">3. Dokumen dan Kelayakan</h3>
            <p>
                Penyewa bertanggung jawab atas kebenaran data diri, nomor paspor, status SIM,
                nomor telepon, dan alamat tempat tinggal saat ini. Admin berhak menolak pengajuan
                apabila data dianggap tidak valid atau tidak memenuhi ketentuan.
            </p>

            <h3 class="mb-3 mt-6 text-lg font-black text-bali-navy">4. Kerusakan dan Pelanggaran</h3>
            <ul class="list-disc space-y-2 pl-5">
                <li>Kerusakan akibat kelalaian penyewa menjadi tanggung jawab penyewa.</li>
                <li>Denda atau sanksi akibat pelanggaran lalu lintas menjadi tanggung jawab penyewa.</li>
                <li>Laporan berkendara ugal-ugalan dapat memengaruhi Safety Score dan pengajuan berikutnya.</li>
                <li>Admin berhak mencatat pelanggaran sebagai bagian dari evaluasi penyewaan.</li>
            </ul>

            <h3 class="mb-3 mt-6 text-lg font-black text-bali-navy">5. Pengembalian Motor</h3>
            <p>
                Motor wajib dikembalikan sesuai jadwal, lokasi, dan kondisi yang disepakati.
                Apabila terdapat keterlambatan, kerusakan, atau kehilangan perlengkapan,
                penyewa dapat dikenakan biaya tambahan.
            </p>

            <h3 class="mb-3 mt-6 text-lg font-black text-bali-navy">6. Persetujuan Digital</h3>
            <p>
                Dengan menyetujui ketentuan ini, penyewa menyatakan telah membaca, memahami,
                dan bersedia mematuhi seluruh aturan selama masa penyewaan motor.
            </p>
        </div>

        <div class="border-t border-bali-line p-6">
            <label class="flex items-start gap-3 rounded-2xl bg-slate-100 p-4 text-sm font-semibold text-bali-muted">
                <input
                    id="termsCheckbox"
                    type="checkbox"
                    disabled
                    class="mt-1 h-4 w-4 rounded border-bali-line text-bali-teal focus:ring-bali-teal"
                >
                <span>
                    Saya telah membaca seluruh Terms & Condition dan setuju untuk mematuhi aturan selama masa sewa.
                    Checkbox ini aktif setelah Anda scroll sampai bagian akhir. Ya, harus dibaca. Peradaban menuntut sedikit usaha.
                </span>
            </label>

            @error('terms_accepted')
                <p class="mt-3 text-sm font-bold text-red-600">{{ $message }}</p>
            @enderror

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="closeTermsModal" class="btn-light">
                    Tolak / Kembali
                </button>

                <button type="button" id="submitBookingButton" class="btn-primary disabled:cursor-not-allowed disabled:opacity-50" disabled>
                    Setuju dan Kirim Pengajuan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('bookingForm');
        const modal = document.getElementById('termsModal');
        const openButton = document.getElementById('openTermsModal');
        const closeButton = document.getElementById('closeTermsModal');
        const termsContent = document.getElementById('termsContent');
        const termsCheckbox = document.getElementById('termsCheckbox');
        const submitButton = document.getElementById('submitBookingButton');
        const termsAcceptedInput = document.getElementById('termsAcceptedInput');

        const openModal = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            termsContent.scrollTop = 0;
            termsCheckbox.checked = false;
            termsCheckbox.disabled = true;
            submitButton.disabled = true;
            termsAcceptedInput.value = '0';
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            termsAcceptedInput.value = '0';
        };

        openButton.addEventListener('click', openModal);
        closeButton.addEventListener('click', closeModal);

        termsContent.addEventListener('scroll', () => {
            const reachedBottom = termsContent.scrollTop + termsContent.clientHeight >= termsContent.scrollHeight - 10;

            if (reachedBottom) {
                termsCheckbox.disabled = false;
            }
        });

        termsCheckbox.addEventListener('change', () => {
            submitButton.disabled = !termsCheckbox.checked;
        });

        submitButton.addEventListener('click', () => {
            if (!termsCheckbox.checked) {
                return;
            }

            termsAcceptedInput.value = '1';
            form.submit();
        });

        form.addEventListener('submit', (event) => {
            if (termsAcceptedInput.value !== '1') {
                event.preventDefault();
                openModal();
            }
        });
    });
</script>
@endsection