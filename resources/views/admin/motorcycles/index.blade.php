@extends('layouts.admin')

@section('page-title', 'Kelola Motor')

@section('content')
@if (session('success'))
    <div class="mb-8 rounded-2xl border border-teal-200 bg-teal-50 p-5 text-sm font-semibold text-bali-teal-dark">
        {{ session('success') }}
    </div>
@endif

<div class="rounded-[2rem] border border-bali-line bg-white p-6 shadow-xl">
    <form method="GET" action="{{ route('admin.motorcycles.index') }}" class="grid gap-4 lg:grid-cols-[1fr_260px_auto_auto] lg:items-end">
        <div>
            <label for="search" class="mb-2 block text-sm font-black text-bali-navy">Pencarian</label>
            <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Brand, model, jenis, atau plat" class="input-ui">
        </div>

        <div>
            <label for="status" class="mb-2 block text-sm font-black text-bali-navy">Status</label>
            <select id="status" name="status" class="input-ui">
                <option value="">Semua Status</option>
                @foreach ($statusLabels as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="rounded-2xl bg-bali-navy px-6 py-4 text-sm font-black text-white transition hover:bg-bali-slate">Filter</button>
        <a href="{{ route('admin.motorcycles.create') }}" class="rounded-2xl bg-bali-orange px-6 py-4 text-center text-sm font-black text-white transition hover:bg-bali-orange-dark">Tambah Motor</a>
    </form>
</div>

<div class="mt-8 rounded-[2rem] border border-bali-line bg-white shadow-xl">
    <div class="border-b border-bali-line p-6">
        <h2 class="text-2xl font-black text-bali-navy">Daftar Motor</h2>
        <p class="mt-2 text-sm text-bali-muted">Kelola unit motor, foto, harga, plat nomor, dan status operasional.</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[980px] text-left text-sm">
            <thead class="bg-slate-100 text-xs uppercase tracking-wide text-bali-muted">
                <tr>
                    <th class="px-6 py-4">Motor</th>
                    <th class="px-6 py-4">Plat</th>
                    <th class="px-6 py-4">Jenis</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-bali-line">
                @forelse ($motorcycles as $motorcycle)
                    <tr class="align-top transition hover:bg-slate-50">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="flex h-16 w-20 items-center justify-center overflow-hidden rounded-2xl bg-slate-100 text-xs font-black text-bali-navy">
                                    @if ($motorcycle->image)
                                        <img src="{{ asset('storage/' . $motorcycle->image) }}" alt="{{ $motorcycle->brand }} {{ $motorcycle->model }}" class="h-full w-full object-cover">
                                    @else
                                        BM
                                    @endif
                                </div>
                                <div>
                                    <strong class="text-bali-navy">{{ $motorcycle->brand }} {{ $motorcycle->model }}</strong>
                                    <span class="mt-1 block text-xs text-bali-muted">{{ $motorcycle->year ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 font-black text-bali-navy">{{ $motorcycle->plate_number }}</td>
                        <td class="px-6 py-5 text-bali-muted">{{ $motorcycle->type ?? '-' }}</td>
                        <td class="px-6 py-5 font-black text-bali-navy">Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}</td>
                        <td class="px-6 py-5">
                            <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-black text-bali-navy">{{ $motorcycle->statusLabel() }}</span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <a href="{{ route('admin.motorcycles.edit', $motorcycle) }}" class="inline-flex rounded-full bg-bali-navy px-5 py-2 text-sm font-black text-white transition hover:bg-bali-slate">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-bali-muted">Belum ada data motor.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="border-t border-bali-line p-6">
        {{ $motorcycles->links() }}
    </div>
</div>
@endsection
