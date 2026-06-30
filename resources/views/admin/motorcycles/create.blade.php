@extends('layouts.admin')

@section('page-title', 'Tambah Motor')

@section('content')
<section class="rounded-[2rem] border border-bali-line bg-white p-8 shadow-xl">
    <span class="badge-orange">Unit Motor</span>
    <h2 class="mt-4 text-3xl font-black text-bali-navy">Tambah motor baru</h2>

    <form method="POST"
          action="{{ route('admin.motorcycles.store') }}"
          enctype="multipart/form-data"
          class="mt-8">

        @csrf

        @include('admin.motorcycles._form')

    </form>
</section>
@endsection