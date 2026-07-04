@extends('layouts.admin')

@section('page-title', 'Kelola Motor')

@section('content')

@if(session('success'))
<div class="mb-6 rounded-2xl bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 rounded-2xl bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
    {{ session('error') }}
</div>
@endif

{{-- FILTER --}}
<div class="mb-8 flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

    <form
        method="GET"
        action="{{ route('admin.motorcycles.index') }}"
        class="flex flex-1 flex-col gap-3 md:flex-row"
    >

        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari brand atau model..."
            class="input-ui flex-1"
        >

        <select
            name="status"
            class="input-ui w-full md:w-56"
        >

            <option value="">
                Semua Status
            </option>

            @foreach($statusLabels as $value => $label)

                <option
                    value="{{ $value }}"
                    @selected(request('status') == $value)
                >
                    {{ $label }}
                </option>

            @endforeach

        </select>

        <button class="btn-dark whitespace-nowrap">
            Filter
        </button>

    </form>

    <a
        href="{{ route('admin.motorcycles.create') }}"
        class="btn-primary whitespace-nowrap"
    >
        + Tambah Jenis Motor
    </a>

</div>

{{-- CARD LIST --}}
<div class="grid gap-6">

@forelse($motorcycles as $motorcycle)

@php

$totalUnit = $motorcycle->stocks->count();

$availableUnit = $motorcycle->stocks
    ->where('status', \App\Models\MotorcycleStock::STATUS_AVAILABLE)
    ->count();

$bookedUnit = $motorcycle->stocks
    ->where('status', \App\Models\MotorcycleStock::STATUS_BOOKED)
    ->count();

$rentedUnit = $motorcycle->stocks
    ->where('status', \App\Models\MotorcycleStock::STATUS_RENTED)
    ->count();

$maintenanceUnit = $motorcycle->stocks
    ->where('status', \App\Models\MotorcycleStock::STATUS_MAINTENANCE)
    ->count();

$statusClass = match($motorcycle->status){

    'available' => 'bg-emerald-100 text-emerald-700',

    'maintenance' => 'bg-amber-100 text-amber-700',

    'unavailable' => 'bg-red-100 text-red-700',

    default => 'bg-slate-100 text-slate-700',

};

@endphp

<div class="rounded-3xl bg-white shadow-sm transition hover:shadow-lg">

    <div class="p-6">

        <div class="flex flex-col gap-6 lg:flex-row">

            {{-- FOTO --}}
            <div class="w-full lg:w-60">

                <div class="h-44 overflow-hidden rounded-2xl bg-slate-100">

                    @if($motorcycle->image)

                        <img
                            src="{{ asset('storage/'.$motorcycle->image) }}"
                            class="h-full w-full object-cover"
                        >

                    @else

                        <div class="flex h-full items-center justify-center text-sm font-bold text-slate-400">
                            No Image
                        </div>

                    @endif

                </div>

            </div>

            {{-- DETAIL --}}
            <div class="flex-1">

                <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">

                    <div>

                        <h2 class="text-2xl font-black text-bali-navy">

                            {{ $motorcycle->brand }}

                            {{ $motorcycle->model }}

                        </h2>

                        <div class="mt-2 text-sm text-slate-500">

                            {{ $motorcycle->type ?: 'Motor' }}

                            •

                            Tahun {{ $motorcycle->year ?: '-' }}

                        </div>

                    </div>

                    <span class="rounded-full px-4 py-2 text-xs font-bold {{ $statusClass }}">

                        {{ $motorcycle->statusLabel() }}

                    </span>

                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-5">

                    <div class="rounded-2xl bg-slate-50 p-4">

                        <div class="text-xs uppercase tracking-wide text-slate-500">
                            Total Unit
                        </div>

                        <div class="mt-2 text-2xl font-black">
                            {{ $totalUnit }}
                        </div>

                    </div>

                    <div class="rounded-2xl bg-emerald-50 p-4">

                        <div class="text-xs uppercase tracking-wide text-emerald-600">
                            Available
                        </div>

                        <div class="mt-2 text-2xl font-black text-emerald-700">
                            {{ $availableUnit }}
                        </div>

                    </div>

                    <div class="rounded-2xl bg-sky-50 p-4">

                        <div class="text-xs uppercase tracking-wide text-sky-600">
                            Booked
                        </div>

                        <div class="mt-2 text-2xl font-black text-sky-700">
                            {{ $bookedUnit }}
                        </div>

                    </div>

                    <div class="rounded-2xl bg-orange-50 p-4">

                        <div class="text-xs uppercase tracking-wide text-orange-600">
                            Disewa
                        </div>

                        <div class="mt-2 text-2xl font-black text-orange-700">
                            {{ $rentedUnit }}
                        </div>

                    </div>

                    <div class="rounded-2xl bg-red-50 p-4">

                        <div class="text-xs uppercase tracking-wide text-red-600">
                            Maintenance
                        </div>

                        <div class="mt-2 text-2xl font-black text-red-700">
                            {{ $maintenanceUnit }}
                        </div>

                    </div>

                </div>

                <div class="mt-6 flex flex-col gap-4 border-t pt-6 lg:flex-row lg:items-center lg:justify-between">

                    <div>

                        <div class="text-xs uppercase tracking-wide text-slate-500">
                            Harga Sewa
                        </div>

                        <div class="mt-1 text-3xl font-black text-bali-navy">

                            Rp{{ number_format($motorcycle->price_per_day,0,',','.') }}

                            <span class="text-base font-semibold text-slate-400">
                                / Hari
                            </span>

                        </div>

                    </div>

                    <div class="flex flex-wrap gap-2">

                        <a
                            href="{{ route('admin.motorcycle-stocks.index',['motorcycle_id'=>$motorcycle->id]) }}"
                            class="rounded-xl bg-bali-navy px-5 py-3 text-sm font-bold text-white"
                        >
                            Kelola Unit
                        </a>

                        <a
                            href="{{ route('admin.motorcycles.edit',$motorcycle) }}"
                            class="rounded-xl bg-slate-700 px-5 py-3 text-sm font-bold text-white"
                        >
                            Edit Motor
                        </a>
@if($motorcycle->canBeDeleted())

<form
    action="{{ route('admin.motorcycles.destroy',$motorcycle) }}"
    method="POST"
    onsubmit="return handleDelete(event,this)"
>
    @csrf
    @method('DELETE')

    <button
        type="submit"
        class="rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white"
    >
        Hapus
    </button>

</form>

@else

<button
    disabled
    class="cursor-not-allowed rounded-xl bg-slate-300 px-5 py-3 text-sm font-bold text-white"
>
    Tidak Bisa Dihapus
</button>

@endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@empty

<div class="rounded-3xl bg-white p-12 text-center shadow">

    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-100 text-4xl">
        🛵
    </div>

    <h2 class="mt-6 text-2xl font-black text-bali-navy">

        Belum Ada Jenis Motor

    </h2>

    <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">

        Tambahkan jenis motor terlebih dahulu.
        Setelah itu Anda dapat menambahkan unit-unit motor pada menu
        <strong>Kelola Unit</strong>.

    </p>

    <a
        href="{{ route('admin.motorcycles.create') }}"
        class="btn-primary mt-8 inline-flex"
    >
        Tambah Jenis Motor
    </a>

</div>

@endforelse

</div>

{{-- PAGINATION --}}
<div class="mt-8">

    {{ $motorcycles->links() }}

</div>

<script>

function handleDelete(event,form)
{
    event.preventDefault();

    if(!confirm('Hapus jenis motor ini?'))
    {
        return false;
    }

    const button=form.querySelector('button');

    button.disabled=true;

    button.innerText='Menghapus...';

    form.submit();
}

</script>

@endsection