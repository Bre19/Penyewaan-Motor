@extends('layouts.admin')

@section('page-title', 'Tambah Motor')

@section('content')

<section class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">

    <div class="flex flex-col gap-3 border-b border-bali-line pb-6">
        <span class="badge-orange">
            Katalog Motor
        </span>

        <div>
            <h2 class="text-3xl font-black text-bali-navy">
                Tambah Jenis Motor
            </h2>

            <p class="mt-2 text-sm text-bali-muted">
                Tambahkan jenis motor baru beserta stok awal unit yang tersedia.
                Setiap stok akan dibuat sebagai unit motor tersendiri sehingga
                nantinya dapat memiliki plat nomor, foto, status, dan GPS masing-masing.
            </p>
        </div>
    </div>

    <form
        method="POST"
        action="{{ route('admin.motorcycles.store') }}"
        enctype="multipart/form-data"
        class="mt-8"
    >
        @csrf

        @include('admin.motorcycles._form')

    </form>

</section>

@endsection