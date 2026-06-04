@extends('layouts.public')

@section('content')
<section class="bg-gradient-to-br from-bali-navy via-slate-900 to-blue-950 py-16 text-white">
    <div class="mx-auto w-[min(1180px,92%)]">
        <span class="mb-3 inline-flex text-xs font-black uppercase tracking-[0.18em] text-bali-teal">
            Dashboard
        </span>
        <h1 class="text-4xl font-black tracking-[-0.04em]">
            Selamat Datang, {{ auth()->user()->name }}
        </h1>
        <p class="mt-4 max-w-2xl leading-8 text-slate-300">
            Area ini akan digunakan untuk melihat status dokumen, pengajuan sewa, pembayaran, dan riwayat penyewaan.
        </p>
    </div>
</section>

<section class="py-16">
    <div class="mx-auto grid w-[min(1180px,92%)] gap-6 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
            <span class="text-sm font-bold text-bali-muted">Status Dokumen</span>
            <strong class="mt-3 block text-2xl font-black text-bali-navy">Belum Lengkap</strong>
            <p class="mt-3 text-sm leading-6 text-bali-muted">Upload paspor, visa, SIM jika ada, dan tanda tangan digital.</p>
        </div>

        <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
            <span class="text-sm font-bold text-bali-muted">Pengajuan Aktif</span>
            <strong class="mt-3 block text-2xl font-black text-bali-navy">0</strong>
            <p class="mt-3 text-sm leading-6 text-bali-muted">Jumlah pengajuan penyewaan yang sedang diproses.</p>
        </div>

        <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
            <span class="text-sm font-bold text-bali-muted">Pembayaran</span>
            <strong class="mt-3 block text-2xl font-black text-bali-navy">Belum Ada</strong>
            <p class="mt-3 text-sm leading-6 text-bali-muted">Status pembayaran akan muncul setelah booking disetujui.</p>
        </div>

        <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
            <span class="text-sm font-bold text-bali-muted">Status Sewa</span>
            <strong class="mt-3 block text-2xl font-black text-bali-navy">Tidak Aktif</strong>
            <p class="mt-3 text-sm leading-6 text-bali-muted">Status penyewaan akan berubah sesuai proses admin.</p>
        </div>
    </div>
</section>
@endsection