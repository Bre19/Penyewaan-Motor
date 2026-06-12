@extends('layouts.guest')

@section('content')
<div>
    <div class="mb-7 text-center">
        <h1 class="text-2xl font-black text-bali-navy">Register Penyewa</h1>
        <p class="mt-2 text-sm leading-6 text-bali-muted">
            Buat akun dan lengkapi dokumen agar pengajuan sewa dapat diverifikasi admin.
        </p>
    </div>

    <form id="register-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
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
                class="input-ui"
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
                class="input-ui"
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
                class="input-ui"
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
                class="textarea-ui"
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
                class="input-ui"
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
                class="input-ui"
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
                class="input-ui"
            >
                <option value="">Pilih status SIM</option>
                <option value="1" {{ old('has_license') === '1' ? 'selected' : '' }}>Ada</option>
                <option value="0" {{ old('has_license') === '0' ? 'selected' : '' }}>Tidak Ada</option>
            </select>

            @error('has_license')
                <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-[1.5rem] border border-bali-line bg-slate-50 p-5">
            <h2 class="text-lg font-black text-bali-navy">Dokumen Verifikasi</h2>
            <p class="mt-2 text-sm leading-6 text-bali-muted">
                Format yang diterima: JPG, JPEG, PNG, atau PDF. Maksimal 4 MB per file.
            </p>

            <div class="mt-5 space-y-5">
                <div>
                    <label for="passport_file" class="mb-2 block text-sm font-black text-bali-navy">
                        Upload Paspor <span class="text-red-600">*</span>
                    </label>
                    <input
                        id="passport_file"
                        type="file"
                        name="passport_file"
                        required
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="block w-full rounded-2xl border border-bali-line bg-white px-4 py-3 text-sm text-bali-muted outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-bali-teal file:px-4 file:py-2 file:text-sm file:font-black file:text-white hover:file:bg-bali-teal-dark"
                    >

                    @error('passport_file')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="visa_file" class="mb-2 block text-sm font-black text-bali-navy">
                        Upload Visa <span class="text-red-600">*</span>
                    </label>
                    <input
                        id="visa_file"
                        type="file"
                        name="visa_file"
                        required
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="block w-full rounded-2xl border border-bali-line bg-white px-4 py-3 text-sm text-bali-muted outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-bali-teal file:px-4 file:py-2 file:text-sm file:font-black file:text-white hover:file:bg-bali-teal-dark"
                    >

                    @error('visa_file')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="license_file" class="mb-2 block text-sm font-black text-bali-navy">
                        Upload SIM
                    </label>
                    <input
                        id="license_file"
                        type="file"
                        name="license_file"
                        accept=".jpg,.jpeg,.png,.pdf"
                        class="block w-full rounded-2xl border border-bali-line bg-white px-4 py-3 text-sm text-bali-muted outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-bali-orange file:px-4 file:py-2 file:text-sm file:font-black file:text-white hover:file:bg-bali-orange-dark"
                    >
                    <p class="mt-2 text-xs font-semibold text-bali-muted">
                        Wajib diisi jika memilih memiliki SIM.
                    </p>

                    @error('license_file')
                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="rounded-[1.5rem] border border-bali-line bg-slate-50 p-5">
            <label class="mb-2 block text-sm font-black text-bali-navy">
                Tanda Tangan Digital <span class="text-red-600">*</span>
            </label>

            <div class="overflow-hidden rounded-2xl border border-bali-line bg-white">
                <canvas id="signature-pad" class="block h-44 w-full cursor-crosshair touch-none"></canvas>
            </div>

            <input type="hidden" name="digital_signature" id="digital_signature">

            <div class="mt-3 flex items-center justify-between gap-3">
                <p class="text-xs font-semibold leading-5 text-bali-muted">
                    Gunakan mouse atau layar sentuh untuk tanda tangan.
                </p>
                <button
                    type="button"
                    id="clear-signature"
                    class="rounded-full bg-slate-200 px-4 py-2 text-xs font-black text-bali-navy transition hover:bg-slate-300"
                >
                    Bersihkan
                </button>
            </div>

            <p id="signature-error" class="mt-2 hidden text-sm font-semibold text-red-600">
                Tanda tangan digital wajib diisi.
            </p>

            @error('digital_signature')
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
                class="input-ui"
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
                class="input-ui"
                placeholder="Ulangi password"
            >
        </div>

        <div class="rounded-2xl bg-slate-100 p-4 text-sm leading-6 text-bali-muted">
            <strong class="block text-bali-navy">Catatan Verifikasi:</strong>
            Data dan dokumen ini digunakan admin untuk memeriksa kelayakan penyewa sebelum pengajuan sewa disetujui.
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register-form');
    const canvas = document.getElementById('signature-pad');
    const signatureInput = document.getElementById('digital_signature');
    const clearButton = document.getElementById('clear-signature');
    const errorText = document.getElementById('signature-error');

    if (!form || !canvas || !signatureInput || !clearButton || !errorText) {
        return;
    }

    const context = canvas.getContext('2d');
    let drawing = false;
    let hasSigned = false;

    const resizeCanvas = () => {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const rect = canvas.getBoundingClientRect();

        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;

        context.setTransform(ratio, 0, 0, ratio, 0, 0);
        context.lineWidth = 2;
        context.lineCap = 'round';
        context.lineJoin = 'round';
        context.strokeStyle = '#07111f';
    };

    const getPosition = (event) => {
        const rect = canvas.getBoundingClientRect();

        return {
            x: event.clientX - rect.left,
            y: event.clientY - rect.top,
        };
    };

    const startDrawing = (event) => {
        drawing = true;
        hasSigned = true;
        errorText.classList.add('hidden');

        const position = getPosition(event);
        context.beginPath();
        context.moveTo(position.x, position.y);
    };

    const draw = (event) => {
        if (!drawing) {
            return;
        }

        const position = getPosition(event);
        context.lineTo(position.x, position.y);
        context.stroke();
    };

    const stopDrawing = () => {
        drawing = false;
    };

    resizeCanvas();

    window.addEventListener('resize', resizeCanvas);

    canvas.addEventListener('pointerdown', startDrawing);
    canvas.addEventListener('pointermove', draw);
    canvas.addEventListener('pointerup', stopDrawing);
    canvas.addEventListener('pointerleave', stopDrawing);
    canvas.addEventListener('pointercancel', stopDrawing);

    clearButton.addEventListener('click', () => {
        context.clearRect(0, 0, canvas.width, canvas.height);
        signatureInput.value = '';
        hasSigned = false;
    });

    form.addEventListener('submit', (event) => {
        if (!hasSigned) {
            event.preventDefault();
            errorText.classList.remove('hidden');
            return;
        }

        signatureInput.value = canvas.toDataURL('image/png');
    });
});
</script>
@endsection