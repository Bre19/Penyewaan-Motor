@extends('layouts.admin')

@section('page-title', 'Unit Motor')

@section('content')

@if(session('success'))
<div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-700 font-semibold">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-red-700 font-semibold">
    {{ session('error') }}
</div>
@endif

{{-- HEADER --}}
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">

    <div>

        <span class="badge-orange">
            Fleet Management
        </span>

        <h2 class="mt-3 text-3xl font-black text-bali-navy">
            Unit Motor
        </h2>

        <p class="mt-2 text-bali-muted">
            Kelola seluruh unit kendaraan beserta status penyewaan dan lokasi GPS.
        </p>

    </div>

    <a href="{{ route('admin.motorcycle-stocks.create') }}"
       class="btn-primary">
        + Tambah Unit
    </a>

</div>

{{-- STATISTIC --}}
<div class="grid gap-5 lg:grid-cols-5 mb-8">

    <div class="rounded-3xl bg-white shadow-lg p-6">

        <div class="text-sm text-bali-muted">
            Total Unit
        </div>

        <div class="mt-2 text-4xl font-black">
            {{ $stats['total'] }}
        </div>

    </div>

    <div class="rounded-3xl bg-emerald-50 shadow-lg p-6">

        <div class="text-sm text-emerald-700">
            Available
        </div>

        <div class="mt-2 text-4xl font-black text-emerald-700">
            {{ $stats['available'] }}
        </div>

    </div>

    <div class="rounded-3xl bg-blue-50 shadow-lg p-6">

        <div class="text-sm text-blue-700">
            Booked
        </div>

        <div class="mt-2 text-4xl font-black text-blue-700">
            {{ $stats['booked'] }}
        </div>

    </div>

    <div class="rounded-3xl bg-orange-50 shadow-lg p-6">

        <div class="text-sm text-orange-700">
            Sedang Disewa
        </div>

        <div class="mt-2 text-4xl font-black text-orange-700">
            {{ $stats['rented'] }}
        </div>

    </div>

    <div class="rounded-3xl bg-red-50 shadow-lg p-6">

        <div class="text-sm text-red-700">
            Maintenance
        </div>

        <div class="mt-2 text-4xl font-black text-red-700">
            {{ $stats['maintenance'] }}
        </div>

    </div>

</div>

{{-- FILTER --}}
<div class="rounded-[2rem] bg-white shadow-xl p-8 mb-8">

<form
    method="GET"
    class="grid gap-5 lg:grid-cols-[1fr_240px_220px_auto]"
>

<input
    type="text"
    name="search"
    value="{{ request('search') }}"
    placeholder="Cari plat nomor / kode unit..."
    class="input-ui">

<select
    name="motorcycle_id"
    class="input-ui">

<option value="">
Semua Motor
</option>

@foreach($motorcycles as $motorcycle)

<option
value="{{ $motorcycle->id }}"
@selected(request('motorcycle_id')==$motorcycle->id)
>

{{ $motorcycle->brand }}
{{ $motorcycle->model }}

</option>

@endforeach

</select>

<select
name="status"
class="input-ui">

<option value="">
Semua Status
</option>

@foreach($statusLabels as $value=>$label)

<option
value="{{ $value }}"
@selected(request('status')==$value)
>

{{ $label }}

</option>

@endforeach

</select>

<button class="btn-dark">

Filter

</button>

</form>

</div>

{{-- GPS LIVE TRACKER --}}
<div class="rounded-[2rem] bg-white shadow-xl overflow-hidden mb-8">

    <div class="flex items-center justify-between px-8 py-6 border-b border-bali-line">

        <div>

            <h3 class="text-2xl font-black text-bali-navy">
                Live GPS Tracking
            </h3>

            <p class="mt-2 text-bali-muted">
                Simulasi posisi kendaraan yang sedang disewa.
            </p>

        </div>

        <span class="rounded-full bg-emerald-100 px-4 py-2 text-sm font-bold text-emerald-700">
            {{ count($dummyLocations) }} Kendaraan Online
        </span>

    </div>

    <div class="grid lg:grid-cols-[1.8fr_420px]">

        {{-- MAP --}}
        <div class="relative">

            <img
                src="{{ asset('images/bali-map.png') }}"
                class="w-full h-[640px] object-cover"
            >

            @foreach($dummyLocations as $location)

                @php

                    $top = rand(8,90);

                    $left = rand(8,90);

                @endphp

                <div
                    class="absolute group"
                    style="top:{{ $top }}%; left:{{ $left }}%;"
                >

                    <div class="relative">

                        <div
                            class="h-5 w-5 rounded-full bg-red-600 border-4 border-white shadow-xl animate-pulse">
                        </div>

                        <div
                            class="absolute hidden group-hover:block mt-3 w-72 rounded-2xl bg-white p-4 shadow-2xl z-50">

                            <div class="font-black text-bali-navy">
                                {{ $location['motorcycle'] }}
                            </div>

                            <div class="text-sm mt-2">

                                {{ $location['plate_number'] }}

                            </div>

                            <div class="mt-3 text-sm">

                                Penyewa

                                <strong>

                                    {{ $location['customer'] }}

                                </strong>

                            </div>

                            <div class="mt-2 text-sm">

                                Lokasi

                                <strong>

                                    {{ $location['location'] }}

                                </strong>

                            </div>

                            <div class="mt-2 text-xs text-slate-500">

                                {{ $location['latitude'] }}

                                ,

                                {{ $location['longitude'] }}

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

        {{-- LIST --}}
        <div class="border-l border-bali-line overflow-y-auto max-h-[640px]">

            <div class="p-6">

                <h4 class="font-black text-xl">

                    Kendaraan Aktif

                </h4>

            </div>

            @forelse($dummyLocations as $location)

                <div class="border-t border-bali-line p-6 hover:bg-slate-50">

                    <div class="flex justify-between items-start">

                        <div>

                            <div class="font-black">

                                {{ $location['motorcycle'] }}

                            </div>

                            <div class="text-sm text-slate-500 mt-1">

                                {{ $location['plate_number'] }}

                            </div>

                        </div>

                        <span
                            class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">

                            LIVE

                        </span>

                    </div>

                    <div class="mt-5 space-y-2 text-sm">

                        <div>

                            Penyewa

                            <strong>

                                {{ $location['customer'] }}

                            </strong>

                        </div>

                        <div>

                            Lokasi

                            <strong>

                                {{ $location['location'] }}

                            </strong>

                        </div>

                        <div>

                            GPS

                            <strong>

                                {{ $location['latitude'] }},

                                {{ $location['longitude'] }}

                            </strong>

                        </div>

                        <div class="text-slate-500">

                            Update

                            {{ $location['updated_at'] }}

                        </div>

                    </div>

                </div>

            @empty

                <div class="p-12 text-center text-slate-500">

                    Tidak ada kendaraan yang sedang disewa.

                </div>

            @endforelse

        </div>

    </div>

</div>

{{-- ============================= --}}
{{-- UNIT MOTOR --}}
{{-- ============================= --}}

<div class="rounded-[2rem] bg-white shadow-xl overflow-hidden">

    <div class="flex items-center justify-between px-8 py-6 border-b border-bali-line">

        <div>

            <h3 class="text-2xl font-black text-bali-navy">
                Daftar Unit Motor
            </h3>

            <p class="mt-2 text-bali-muted">
                Kelola seluruh unit kendaraan berdasarkan stok fisik.
            </p>

        </div>

        <div class="text-sm font-semibold text-bali-muted">

            Total Unit

            <span class="text-bali-navy font-black">

                {{ $stocks->total() }}

            </span>

        </div>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-slate-50">

            <tr class="text-left text-sm font-black text-bali-navy">

                <th class="px-6 py-5">
                    Unit
                </th>

                <th class="px-6 py-5">
                    Motor
                </th>

                <th class="px-6 py-5">
                    Plat Nomor
                </th>

                <th class="px-6 py-5">
                    Status
                </th>

                <th class="px-6 py-5">
                    Catatan
                </th>

                <th class="px-6 py-5 text-right">
                    Aksi
                </th>

            </tr>

            </thead>

            <tbody>

            @forelse($stocks as $stock)

                @php

                    $statusClass = match($stock->status){

                        'available' =>
                            'bg-emerald-100 text-emerald-700',

                        'booked' =>
                            'bg-blue-100 text-blue-700',

                        'rented' =>
                            'bg-orange-100 text-orange-700',

                        'maintenance' =>
                            'bg-red-100 text-red-700',

                        default =>
                            'bg-slate-100 text-slate-700',

                    };

                @endphp

                <tr
                    class="border-t border-bali-line hover:bg-slate-50 transition">

                    {{-- FOTO --}}
                    <td class="px-6 py-5">

                        <div class="flex items-center gap-4">

                            @if($stock->image)

                                <img
                                    src="{{ asset('storage/'.$stock->image) }}"
                                    class="w-20 h-16 rounded-xl object-cover">

                            @else

                                <div
                                    class="w-20 h-16 rounded-xl bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-500">

                                    NO IMAGE

                                </div>

                            @endif

                            <div>

                                <div class="font-black">

                                    {{ $stock->stock_code }}

                                </div>

                                <div class="text-xs text-slate-500">

                                    Unit #{{ $stock->id }}

                                </div>

                            </div>

                        </div>

                    </td>

                    {{-- MOTOR --}}
                    <td class="px-6 py-5">

                        <div class="font-black">

                            {{ $stock->motorcycle->brand }}

                        </div>

                        <div class="text-sm text-slate-500">

                            {{ $stock->motorcycle->model }}

                        </div>

                    </td>

                    {{-- PLAT --}}
                    <td class="px-6 py-5">

                        <div class="font-semibold">

                            {{ $stock->plate_number }}

                        </div>

                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-5">

                        <span
                            class="rounded-full px-4 py-2 text-xs font-bold {{ $statusClass }}">

                            {{ $stock->statusLabel() }}

                        </span>

                    </td>

                    {{-- NOTE --}}
                    <td class="px-6 py-5">

                        <div class="text-sm text-slate-600">

                            {{ $stock->notes ?: '-' }}

                        </div>

                    </td>

                    {{-- ACTION --}}
                    <td class="px-6 py-5">

                        <div class="flex justify-end gap-2">

                            <a
                                href="{{ route('admin.motorcycle-stocks.edit',$stock) }}"
                                class="btn-light">

                                Edit

                            </a>

                            @if($stock->canBeDeleted())

                                <form
                                    method="POST"
                                    action="{{ route('admin.motorcycle-stocks.destroy',$stock) }}"
                                    onsubmit="return confirm('Hapus unit motor ini?')">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn-danger">

                                        Hapus

                                    </button>

                                </form>

                            @else

                                <button
                                    disabled
                                    class="rounded-xl bg-slate-200 px-4 py-2 text-sm font-bold text-slate-500">

                                    Dipakai

                                </button>

                            @endif

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="6"
                        class="text-center py-20">

                        <div
                            class="text-2xl font-black text-bali-navy">

                            Belum ada Unit Motor

                        </div>

                        <div
                            class="mt-3 text-bali-muted">

                            Tambahkan unit pertama agar dapat mulai disewakan.

                        </div>

                        <a
                            href="{{ route('admin.motorcycle-stocks.create') }}"
                            class="btn-primary mt-8 inline-flex">

                            Tambah Unit

                        </a>

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="border-t border-bali-line p-6">

        {{ $stocks->links() }}

    </div>

</div>

@endsection