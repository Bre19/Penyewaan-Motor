@extends('layouts.guest')

@section('content')
<div>
    <div class="mb-7 text-center">
        <h1 class="text-2xl font-black text-bali-navy">Login Akun</h1>
        <p class="mt-2 text-sm leading-6 text-bali-muted">
            Masuk untuk melihat dashboard, status dokumen, dan pengajuan sewa.
        </p>
    </div>

    @if (session('status'))
        <div class="mb-5 rounded-2xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm font-semibold text-bali-teal-dark">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

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
                autofocus
                autocomplete="username"
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                placeholder="nama@email.com"
            >

            @error('email')
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
                autocomplete="current-password"
                class="h-13 w-full rounded-2xl border border-bali-line px-4 text-sm outline-none transition focus:border-bali-teal focus:ring-2 focus:ring-bali-teal/20"
                placeholder="Masukkan password"
            >

            @error('password')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between gap-4">
            <label class="flex items-center gap-2 text-sm font-semibold text-bali-muted">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-bali-line text-bali-teal focus:ring-bali-teal"
                >
                Ingat saya
            </label>
        </div>

        <button
            type="submit"
            class="w-full rounded-full bg-bali-navy px-6 py-4 text-sm font-black text-white transition hover:bg-bali-slate"
        >
            Login
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-bali-muted">
        Belum punya akun?
        <a href="{{ route('register') }}" class="font-black text-bali-orange hover:text-bali-orange-dark">
            Register sekarang
        </a>
    </p>

    <div class="mt-6 rounded-2xl bg-slate-100 p-4 text-sm leading-6 text-bali-muted">
        <strong class="block text-bali-navy">Catatan:</strong>
        Setelah login, penyewa dapat melengkapi dokumen seperti paspor, visa, SIM, dan tanda tangan digital.
    </div>
</div>
@endsection