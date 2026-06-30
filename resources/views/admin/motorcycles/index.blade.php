@extends('layouts.admin')

@section('page-title', 'Kelola Motor')

@section('content')

@if (session('success'))
    <div class="mb-6 rounded-2xl bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-6 rounded-2xl bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
        {{ session('error') }}
    </div>
@endif

{{-- FILTER --}}
<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

    <form method="GET" action="{{ route('admin.motorcycles.index') }}"
          class="flex flex-1 flex-col gap-3 sm:flex-row">

        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari motor..."
            class="input-ui w-full">

        <select name="status" class="input-ui w-full sm:w-52">
            <option value="">Semua Status</option>
            @foreach ($statusLabels as $value => $label)
                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <button class="btn-dark whitespace-nowrap">Filter</button>
    </form>

    <a href="{{ route('admin.motorcycles.create') }}"
       class="btn-primary text-center whitespace-nowrap">
        + Tambah Motor
    </a>
</div>

{{-- LIST CARD --}}
<div class="grid gap-5">

@forelse ($motorcycles as $motorcycle)

    <div class="rounded-2xl bg-white p-5 shadow-sm hover:shadow-md transition">

        <div class="flex flex-col gap-4 md:flex-row md:items-center">

            {{-- IMAGE --}}
            <div class="w-full md:w-40 h-28 rounded-xl bg-slate-100 overflow-hidden flex items-center justify-center text-xs font-bold text-slate-400">
                @if ($motorcycle->image)
                    <img src="{{ asset('storage/' . $motorcycle->image) }}"
                         class="w-full h-full object-cover">
                @else
                    No Image
                @endif
            </div>

            {{-- INFO --}}
            <div class="flex-1">

                <div class="flex items-start justify-between gap-4">

                    <div>
                        <div class="text-lg font-black text-bali-ink">
                            {{ $motorcycle->brand }} {{ $motorcycle->model }}
                        </div>

                        <div class="text-sm text-slate-500 mt-1">
                            {{ $motorcycle->year ?? '-' }} • {{ $motorcycle->plate_number }}
                        </div>

                        @if ($motorcycle->hasActiveBooking())
                            <div class="mt-2 text-xs font-semibold text-red-500">
                                Sedang dipakai
                            </div>
                        @endif
                    </div>

                    {{-- STATUS --}}
                    @php
                        $statusClass = match($motorcycle->status) {
                            'available' => 'bg-emerald-100 text-emerald-700',
                            'maintenance' => 'bg-amber-100 text-amber-700',
                            'unavailable' => 'bg-red-100 text-red-700',
                            default => 'bg-slate-100 text-slate-700'
                        };
                    @endphp

                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }}">
                        {{ $motorcycle->statusLabel() }}
                    </span>

                </div>

                {{-- PRICE + ACTION --}}
                <div class="mt-4 flex items-center justify-between">

                    <div class="text-lg font-black text-bali-navy">
                        Rp{{ number_format($motorcycle->price_per_day, 0, ',', '.') }}
                        <span class="text-xs font-semibold text-slate-400">/hari</span>
                    </div>

                    <div class="flex gap-2">

                        {{-- EDIT --}}
                        <a href="{{ route('admin.motorcycles.edit', $motorcycle) }}"
                           class="px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-bold hover:opacity-90">
                            Edit
                        </a>

                        {{-- DELETE --}}
                        @if ($motorcycle->canBeDeleted())
                            <form action="{{ route('admin.motorcycles.destroy', $motorcycle) }}"
                                  method="POST"
                                  onsubmit="return handleDelete(event, this)">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-bold hover:opacity-90">
                                    Hapus
                                </button>
                            </form>
                        @else
                            <button disabled
                                    class="px-4 py-2 rounded-xl bg-gray-300 text-white text-xs font-bold cursor-not-allowed"
                                    title="Motor sedang digunakan">
                                Tidak bisa hapus
                            </button>
                        @endif

                    </div>

                </div>

            </div>
        </div>

    </div>

@empty

    <div class="rounded-2xl bg-white p-10 text-center shadow-sm">
        <div class="text-lg font-semibold text-slate-600">
            Belum ada data motor
        </div>

        <a href="{{ route('admin.motorcycles.create') }}"
           class="btn-primary mt-4 inline-block">
            Tambah Motor Pertama
        </a>
    </div>

@endforelse

</div>

{{-- PAGINATION --}}
<div class="mt-6">
    {{ $motorcycles->links() }}
</div>

{{-- SCRIPT --}}
<script>
function handleDelete(event, form) {
    event.preventDefault();

    if (!confirm('Hapus motor ini secara permanen?')) {
        return false;
    }

    const button = form.querySelector('button');
    button.disabled = true;
    button.innerText = 'Menghapus...';

    form.submit();
}
</script>

@endsection