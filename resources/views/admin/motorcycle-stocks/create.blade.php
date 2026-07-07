@extends('layouts.admin')

@section('page-title', 'Tambah Unit Motor')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="text-sm font-bold uppercase tracking-[0.18em] text-bali-teal">
                Motorcycle Stock
            </div>

            <h2 class="mt-2 text-3xl font-black text-bali-ink">
                Tambah Unit Motor
            </h2>

            <p class="mt-2 max-w-3xl text-sm leading-7 text-bali-muted">
                Tambahkan unit fisik baru yang akan digunakan untuk proses
                penyewaan. Setiap unit memiliki plat nomor, kode unit, status,
                serta foto masing-masing.
            </p>

        </div>

        <a
            href="{{ route('admin.motorcycle-stocks.index') }}"
            class="btn-light"
        >
            Kembali
        </a>

    </div>

    {{-- ERROR --}}
    @if ($errors->any())

        <div class="rounded-3xl border border-red-200 bg-red-50 p-5">

            <div class="font-bold text-red-700">
                Terdapat data yang belum valid.
            </div>

            <ul class="mt-3 list-disc space-y-1 pl-5 text-sm text-red-600">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form
        action="{{ route('admin.motorcycle-stocks.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        <div class="rounded-3xl bg-white shadow-sm">

            {{-- CARD HEADER --}}
            <div class="border-b border-bali-line px-8 py-6">

                <h3 class="text-xl font-black text-bali-ink">
                    Informasi Unit Motor
                </h3>

                <p class="mt-2 text-sm text-bali-muted">
                    Lengkapi seluruh informasi unit sebelum disimpan.
                </p>

            </div>

            {{-- FORM --}}
            <div class="p-8">

                @include('admin.motorcycle-stocks._form')

            </div>

        </div>

    </form>

</div>

@endsection