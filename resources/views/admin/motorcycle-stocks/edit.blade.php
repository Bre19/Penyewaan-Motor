@extends('layouts.admin')

@section('page-title', 'Edit Unit Motor')

@section('content')

<section class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">

    <span class="badge-orange">
        Unit Motor
    </span>

    <h2 class="mt-4 text-3xl font-black text-bali-navy">
        Edit Unit Motor
    </h2>

    <p class="mt-2 max-w-2xl text-sm leading-7 text-bali-muted">
        Perbarui informasi unit motor, termasuk plat nomor, status operasional,
        foto unit, dan catatan internal. Perubahan hanya berlaku untuk unit ini,
        bukan seluruh jenis motor.
    </p>

    <form
        method="POST"
        action="{{ route('admin.motorcycle-stocks.update', $stock) }}"
        enctype="multipart/form-data"
        class="mt-8"
    >

        @csrf
        @method('PUT')

        @include('admin.motorcycle-stocks._form')

    </form>

</section>

@endsection