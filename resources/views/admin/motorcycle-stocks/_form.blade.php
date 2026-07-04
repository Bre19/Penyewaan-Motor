@php
    $isEdit = isset($stock) && $stock->exists;
    $previewId = 'preview-stock-image';
@endphp

<div class="grid gap-6 md:grid-cols-2">

    {{-- MOTOR --}}
    <div>
        <label class="label">
            Jenis Motor
        </label>

        <select name="motorcycle_id" class="input-ui" required>
            <option value="">Pilih Motor</option>

            @foreach($motorcycles as $motorcycle)
                <option
                    value="{{ $motorcycle->id }}"
                    @selected(old('motorcycle_id', $stock->motorcycle_id ?? '') == $motorcycle->id)
                >
                    {{ $motorcycle->brand }}
                    {{ $motorcycle->model }}
                    (Rp{{ number_format($motorcycle->price_per_day,0,',','.') }}/hari)
                </option>
            @endforeach
        </select>

        @error('motorcycle_id')
            <p class="error">{{ $message }}</p>
        @enderror
    </div>

    {{-- STOCK CODE --}}
    <div>
        <label class="label">
            Kode Unit
        </label>

        <input
            type="text"
            name="stock_code"
            value="{{ old('stock_code', $stock->stock_code ?? '') }}"
            class="input-ui"
            required
        >

        @error('stock_code')
            <p class="error">{{ $message }}</p>
        @enderror
    </div>

    {{-- PLATE --}}
    <div>
        <label class="label">
            Plat Nomor
        </label>

        <input
            type="text"
            name="plate_number"
            value="{{ old('plate_number', $stock->plate_number ?? '') }}"
            class="input-ui"
            required
        >

        @error('plate_number')
            <p class="error">{{ $message }}</p>
        @enderror
    </div>

    {{-- STATUS --}}
    <div>
        <label class="label">
            Status Unit
        </label>

        <select
            name="status"
            class="input-ui"
            required
        >
            @foreach($statusLabels as $value => $label)

                <option
                    value="{{ $value }}"
                    @selected(old('status', $stock->status ?? 'available') == $value)
                >
                    {{ $label }}
                </option>

            @endforeach
        </select>

        @error('status')
            <p class="error">{{ $message }}</p>
        @enderror
    </div>

    {{-- IMAGE --}}
    <div class="md:col-span-2">

        <label class="label">
            Foto Unit Motor
        </label>

        <input
            type="file"
            name="image"
            accept=".jpg,.jpeg,.png,.webp"
            class="input-ui h-auto py-3"
            onchange="previewImage(event)"
        >

        @error('image')
            <p class="error">{{ $message }}</p>
        @enderror

        <div class="mt-4">

            @if(!empty($stock->image))
                <img
                    id="{{ $previewId }}"
                    src="{{ asset('storage/'.$stock->image) }}"
                    class="h-32 rounded-xl border object-cover"
                >
            @else
                <img
                    id="{{ $previewId }}"
                    class="hidden h-32 rounded-xl border object-cover"
                >
            @endif

        </div>

    </div>

</div>

{{-- NOTES --}}
<div class="mt-6">

    <label class="label">
        Catatan
    </label>

    <textarea
        name="notes"
        rows="5"
        class="textarea-ui"
    >{{ old('notes', $stock->notes ?? '') }}</textarea>

    @error('notes')
        <p class="error">{{ $message }}</p>
    @enderror

</div>

{{-- ACTION --}}
<div class="mt-8 flex gap-3 border-t border-bali-line pt-6">

    <button
        type="submit"
        class="btn-primary"
    >
        {{ $isEdit ? 'Update Unit Motor' : 'Simpan Unit Motor' }}
    </button>

    <a
        href="{{ route('admin.motorcycle-stocks.index') }}"
        class="btn-light"
    >
        Kembali
    </a>

</div>

<style>

.label{
    @apply mb-2 block text-sm font-black text-bali-navy;
}

.error{
    @apply mt-2 text-sm font-bold text-red-600;
}

</style>

<script>

function previewImage(event)
{
    const file = event.target.files[0];

    if(!file) return;

    const image = document.getElementById('{{ $previewId }}');

    image.src = URL.createObjectURL(file);

    image.classList.remove('hidden');
}

</script>