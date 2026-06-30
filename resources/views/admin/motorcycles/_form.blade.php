@php
    $isEdit = isset($motorcycle);
    $previewId = 'preview-image';
@endphp

<div class="grid gap-6 md:grid-cols-2">

    {{-- BRAND --}}
    <div>
        <label class="label">Brand</label>
        <input type="text" name="brand"
               value="{{ old('brand', $motorcycle->brand ?? '') }}"
               required class="input-ui">
        @error('brand') <p class="error">{{ $message }}</p> @enderror
    </div>

    {{-- MODEL --}}
    <div>
        <label class="label">Model</label>
        <input type="text" name="model"
               value="{{ old('model', $motorcycle->model ?? '') }}"
               required class="input-ui">
        @error('model') <p class="error">{{ $message }}</p> @enderror
    </div>

    {{-- TYPE --}}
    <div>
        <label class="label">Jenis</label>
        <input type="text" name="type"
               value="{{ old('type', $motorcycle->type ?? '') }}"
               class="input-ui">
    </div>

    {{-- YEAR --}}
    <div>
        <label class="label">Tahun</label>
        <input type="number" name="year"
               value="{{ old('year', $motorcycle->year ?? '') }}"
               class="input-ui">
    </div>

    {{-- PLATE --}}
    <div>
        <label class="label">Plat Nomor</label>
        <input type="text" name="plate_number"
               value="{{ old('plate_number', $motorcycle->plate_number ?? '') }}"
               required class="input-ui">
        @error('plate_number') <p class="error">{{ $message }}</p> @enderror
    </div>

    {{-- PRICE --}}
    <div>
        <label class="label">Harga per Hari</label>
        <input type="number" name="price_per_day"
               value="{{ old('price_per_day', $motorcycle->price_per_day ?? '') }}"
               required min="0" class="input-ui">
        @error('price_per_day') <p class="error">{{ $message }}</p> @enderror
    </div>

    {{-- STATUS --}}
    <div>
        <label class="label">Status</label>
        <select name="status" class="input-ui">
            @foreach ($statusLabels as $value => $label)
                <option value="{{ $value }}"
                    {{ old('status', $motorcycle->status ?? 'available') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- IMAGE --}}
    <div>
        <label class="label">Foto Motor</label>

        <input type="file"
               name="image"
               accept="image/png,image/jpeg,image/webp"
               class="input-ui h-auto py-3"
               onchange="previewImage(event, '{{ $previewId }}')">

        {{-- Preview --}}
        <div class="mt-3">
            @if (!empty($motorcycle?->image))
                <img id="{{ $previewId }}"
                     src="{{ asset('storage/' . $motorcycle->image) }}"
                     class="h-24 w-auto rounded-xl object-cover">
            @else
                <img id="{{ $previewId }}"
                     class="hidden h-24 w-auto rounded-xl object-cover">
            @endif
        </div>

        @error('image') <p class="error">{{ $message }}</p> @enderror
    </div>

</div>

{{-- DESCRIPTION --}}
<div class="mt-6">
    <label class="label">Deskripsi</label>
    <textarea name="description" rows="5"
        class="textarea-ui">{{ old('description', $motorcycle->description ?? '') }}</textarea>
    @error('description') <p class="error">{{ $message }}</p> @enderror
</div>

{{-- ACTION --}}
<div class="flex gap-3 border-t border-bali-line pt-6 mt-6">
    <button type="submit" class="btn-primary">
        {{ $isEdit ? 'Update Motor' : 'Simpan Motor' }}
    </button>

    <a href="{{ route('admin.motorcycles.index') }}" class="btn-light">
        Kembali
    </a>
</div>

{{-- STYLE --}}
<style>
.label { @apply mb-2 block text-sm font-black text-bali-navy; }
.error { @apply mt-2 text-sm font-bold text-red-600; }
</style>

{{-- SCRIPT --}}
<script>
function previewImage(event, previewId){
    const file = event.target.files[0];
    if (!file) return;

    const img = document.getElementById(previewId);
    if (!img) return;

    img.src = URL.createObjectURL(file);
    img.classList.remove('hidden');
}
</script>