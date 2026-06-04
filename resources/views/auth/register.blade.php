@extends('layouts.guest')

@section('content')
<div>
    <div class="mb-7 text-center">
        <h1 class="text-2xl font-black text-bali-navy">Register Penyewa</h1>
        <p class="mt-2 text-sm leading-6 text-bali-muted">
            Buat akun untuk mengajukan penyewaan motor secara online.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="mb-2 block text-sm font-black text-bali-navy">
                Nama Lengkap
            </label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                placeholder="Nama sesuai identitas"
            >

            @error('name')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm font-black text-bali-navy">
                Email
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                placeholder="nama@email.com"
            >

            @error('email')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="phone_number" class="mb-2 block text-sm font-black text-bali-navy">
                Nomor Telepon / WhatsApp
            </label>
            <input
                id="phone_number"
                type="text"
                name="phone_number"
                value="{{ old('phone_number') }}"
                required
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                placeholder="Contoh: 081234567890"
            >

            @error('phone_number')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="current_address" class="mb-2 block text-sm font-black text-bali-navy">
                Tempat Tinggal Saat Ini
            </label>
            <textarea
                id="current_address"
                name="current_address"
                required
                rows="3"
                class="w-full rounded-2xl border border-bali-line px-4 py-3 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                placeholder="Alamat tempat tinggal saat ini di Bali"
            >{{ old('current_address') }}</textarea>

            @error('current_address')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="passport_number" class="mb-2 block text-sm font-black text-bali-navy">
                Nomor Paspor
            </label>
            <input
                id="passport_number"
                type="text"
                name="passport_number"
                value="{{ old('passport_number') }}"
                required
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                placeholder="Masukkan nomor paspor"
            >

            @error('passport_number')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="origin_country" class="mb-2 block text-sm font-black text-bali-navy">
                Negara Asal
            </label>
            <input
                id="origin_country"
                type="text"
                name="origin_country"
                value="{{ old('origin_country') }}"
                required
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                placeholder="Contoh: Australia"
            >

            @error('origin_country')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="has_license" class="mb-2 block text-sm font-black text-bali-navy">
                Memiliki SIM?
            </label>
            <select
                id="has_license"
                name="has_license"
                required
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
            >
                <option value="">Pilih status SIM</option>
                <option value="1" {{ old('has_license') === '1' ? 'selected' : '' }}>Ada</option>
                <option value="0" {{ old('has_license') === '0' ? 'selected' : '' }}>Tidak Ada</option>
            </select>

            @error('has_license')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-black text-bali-navy">
                Password
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                placeholder="Minimal 8 karakter"
            >

            @error('password')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-black text-bali-navy">
                Konfirmasi Password
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                placeholder="Ulangi password"
            >
        </div>

        <div class="rounded-2xl bg-slate-100 p-4 text-sm leading-6 text-bali-muted">
            <strong class="block text-bali-navy">Tahap berikutnya:</strong>
            Setelah akun dibuat, penyewa wajib melengkapi dokumen paspor, visa, SIM jika ada,
            dan tanda tangan digital sebelum pengajuan sewa diproses admin.
        </div>

        <button
            type="submit"
            class="w-full rounded-full bg-bali-orange px-6 py-4 text-sm font-black text-white transition hover:bg-bali-orange-dark"
        >
            Register
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-bali-muted">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-black text-bali-teal-dark hover:text-bali-teal">
            Login
        </a>
    </p>
</div>
@endsection