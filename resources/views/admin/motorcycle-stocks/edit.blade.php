@extends('layouts.admin')

@section('page-title', 'Edit Unit Motor')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="text-sm font-bold uppercase tracking-[0.18em] text-bali-teal">
                Motorcycle Stock
            </div>

            <h2 class="mt-2 text-3xl font-black text-bali-ink">
                Edit Unit Motor
            </h2>

            <p class="mt-2 max-w-3xl text-sm leading-7 text-bali-muted">
                Perbarui informasi unit motor seperti kode unit, plat nomor,
                status operasional, foto unit, dan catatan internal. Perubahan
                hanya berlaku pada unit fisik ini dan tidak memengaruhi data
                motor induknya.
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
        action="{{ route('admin.motorcycle-stocks.update', $stock) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        <div class="rounded-3xl bg-white shadow-sm">

            {{-- CARD HEADER --}}
            <div class="border-b border-bali-line px-8 py-6">

                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

                    <div>

                        <h3 class="text-xl font-black text-bali-ink">
                            Informasi Unit Motor
                        </h3>

                        <p class="mt-2 text-sm text-bali-muted">
                            Pastikan seluruh informasi unit sudah sesuai sebelum disimpan.
                        </p>

                    </div>

                    <div class="rounded-2xl bg-bali-soft px-5 py-3">

                        <div class="text-xs font-bold uppercase tracking-wide text-slate-500">
                            ID Unit
                        </div>

                        <div class="mt-1 text-lg font-black text-bali-navy">
                            {{ $stock->stock_code }}
                        </div>

                    </div>

                </div>

            </div>

            {{-- FORM --}}
            <div class="p-8">

                @include('admin.motorcycle-stocks._form')

            </div>

        </div>

    </form>

</div>

@endsection