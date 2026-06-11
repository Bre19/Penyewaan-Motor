@extends('layouts.admin')
@section('page-title', 'Edit Motor')
@section('content')
<div class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
    <h2 class="text-3xl font-black text-bali-navy">Edit Motor</h2>
    <form method="POST" action="{{ route('admin.motorcycles.update', $motorcycle) }}" enctype="multipart/form-data" class="mt-8 space-y-5">
        @csrf
        <input type="hidden" name="_method" value="PUT">
        <input type="text" name="brand" value="{{ old('brand', $motorcycle->brand) }}" required class="input-ui" placeholder="Brand">
        <input type="text" name="model" value="{{ old('model', $motorcycle->model) }}" required class="input-ui" placeholder="Model">
        <input type="text" name="type" value="{{ old('type', $motorcycle->type) }}" class="input-ui" placeholder="Jenis">
        <input type="number" name="year" value="{{ old('year', $motorcycle->year) }}" class="input-ui" placeholder="Tahun">
        <input type="text" name="plate_number" value="{{ old('plate_number', $motorcycle->plate_number) }}" required class="input-ui" placeholder="Plat Nomor">
        <input type="number" name="price_per_day" value="{{ old('price_per_day', $motorcycle->price_per_day) }}" required class="input-ui" placeholder="Harga per Hari">
        <select name="status" required class="input-ui">
            @foreach ($statusLabels as $value => $label)
                <option value="{{ $value }}" {{ old('status', $motorcycle->status) === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <input type="file" name="image" accept="image/*" class="input-ui h-auto py-3">
        <textarea name="description" rows="5" class="textarea-ui" placeholder="Deskripsi">{{ old('description', $motorcycle->description) }}</textarea>
        @if ($errors->any())<div class="rounded-2xl bg-red-50 p-4 text-sm font-bold text-red-700">Input belum sesuai.</div>@endif
        <button type="submit" class="btn-primary">Update Data Motor</button>
        <a href="{{ route('admin.motorcycles.index') }}" class="btn-light">Kembali</a>
    </form>
</div>
@endsection
