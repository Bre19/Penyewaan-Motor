@extends('layouts.admin')

@section('page-title', 'Tambah Motor')

@section('content')
<section class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
    <span class="badge-orange">Unit Motor</span>
    <h2 class="mt-4 text-3xl font-black text-bali-navy">Tambah motor baru</h2>

    <form method="POST" action="{{ route('admin.motorcycles.store') }}" enctype="multipart/form-data" class="mt-8 space-y-6">
        @csrf

        <div class="grid gap-5 md:grid-cols-2">
            <div><label class="mb-2 block text-sm font-black text-bali-navy">Brand</label><input type="text" name="brand" value="{{ old('brand') }}" required class="input-ui">@error('brand')<p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-2 block text-sm font-black text-bali-navy">Model</label><input type="text" name="model" value="{{ old('model') }}" required class="input-ui">@error('model')<p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-2 block text-sm font-black text-bali-navy">Jenis</label><input type="text" name="type" value="{{ old('type') }}" class="input-ui">@error('type')<p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-2 block text-sm font-black text-bali-navy">Tahun</label><input type="number" name="year" value="{{ old('year') }}" class="input-ui">@error('year')<p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-2 block text-sm font-black text-bali-navy">Plat Nomor</label><input type="text" name="plate_number" value="{{ old('plate_number') }}" required class="input-ui">@error('plate_number')<p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-2 block text-sm font-black text-bali-navy">Harga per Hari</label><input type="number" name="price_per_day" value="{{ old('price_per_day') }}" required min="0" step="1000" class="input-ui">@error('price_per_day')<p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-2 block text-sm font-black text-bali-navy">Status</label><select name="status" required class="input-ui">@foreach ($statusLabels as $value => $label)<option value="{{ $value }}" {{ old('status', 'available') === $value ? 'selected' : '' }}>{{ $label }}</option>@endforeach</select>@error('status')<p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>@enderror</div>
            <div><label class="mb-2 block text-sm font-black text-bali-navy">Foto Motor</label><input type="file" name="image" accept="image/*" class="input-ui h-auto py-3">@error('image')<p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>@enderror</div>
        </div>

        <div><label class="mb-2 block text-sm font-black text-bali-navy">Deskripsi</label><textarea name="description" rows="5" class="textarea-ui">{{ old('description') }}</textarea>@error('description')<p class="mt-2 text-sm font-bold text-red-600">{{ $message }}</p>@enderror</div>

        <div class="flex flex-col gap-3 border-t border-bali-line pt-6 sm:flex-row">
            <button type="submit" class="btn-primary">Simpan Data Motor</button>
            <a href="{{ route('admin.motorcycles.index') }}" class="btn-light">Kembali</a>
        </div>
    </form>
</section>
@endsection
