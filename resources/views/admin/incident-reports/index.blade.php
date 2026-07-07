@extends('layouts.admin')

@section('page-title', 'Pelaporan Kejadian')

@section('content')
@php
    // DUMMY DATA — inline to avoid Blade @include scope isolation (see chat notes).
    // Replace with real data source once backend is ready. Field names below
    // must stay in sync with resources/views/admin/incident-reports/show.blade.php.
    $reports = [
        [
            'id' => 1,
            'code' => 'IR-2026-001',
            'status' => 'Baru',
            'priority' => 'High',
            'category' => 'Ugal-ugalan',
            'reporter_name' => 'Made Sudarma',
            'renter_name' => 'John Smith',
            'location' => 'Kuta',
            'reported_at' => '20 Jun 2026, 15:30',
        ],
        [
            'id' => 2,
            'code' => 'IR-2026-002',
            'status' => 'Diproses',
            'priority' => 'Medium',
            'category' => 'Lampu Merah',
            'reporter_name' => 'Wayan Sutrisna',
            'renter_name' => 'Amanda Cole',
            'location' => 'Denpasar',
            'reported_at' => '19 Jun 2026, 09:12',
        ],
        [
            'id' => 3,
            'code' => 'IR-2026-003',
            'status' => 'Diproses',
            'priority' => 'Medium',
            'category' => 'Keluar Area',
            'reporter_name' => 'Sistem GPS',
            'renter_name' => 'Lukas Berger',
            'location' => 'Tabanan',
            'reported_at' => '18 Jun 2026, 21:47',
        ],
        [
            'id' => 4,
            'code' => 'IR-2026-004',
            'status' => 'Selesai',
            'priority' => 'Low',
            'category' => 'Parkir',
            'reporter_name' => 'Petugas Lapangan',
            'renter_name' => 'Sophie Martin',
            'location' => 'Seminyak',
            'reported_at' => '17 Jun 2026, 13:05',
        ],
        [
            'id' => 5,
            'code' => 'IR-2026-005',
            'status' => 'Selesai',
            'priority' => 'Low',
            'category' => 'Knalpot',
            'reporter_name' => 'Masyarakat',
            'renter_name' => 'Marco Ferrari',
            'location' => 'Canggu',
            'reported_at' => '16 Jun 2026, 10:20',
        ],
    ];

    $totalReports = count($reports);
    $newReports = collect($reports)->where('status', 'Baru')->count();
    $processingReports = collect($reports)->where('status', 'Diproses')->count();
    $completedReports = collect($reports)->where('status', 'Selesai')->count();

    $statusColors = [
        'Baru' => 'bg-red-100 text-red-700',
        'Diproses' => 'bg-amber-100 text-amber-700',
        'Selesai' => 'bg-emerald-100 text-emerald-700',
    ];

    $priorityColors = [
        'Low' => 'bg-emerald-100 text-emerald-700',
        'Medium' => 'bg-amber-100 text-amber-700',
        'High' => 'bg-orange-100 text-orange-700',
        'Critical' => 'bg-red-100 text-red-700',
    ];
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="text-sm font-black uppercase tracking-[0.18em] text-bali-teal">
                Operational Monitoring
            </div>
            <h2 class="mt-2 text-3xl font-black text-bali-ink">
                Pelaporan Kejadian
            </h2>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-bali-muted">
                Monitoring seluruh laporan insiden yang terjadi selama masa
                penyewaan kendaraan, baik berasal dari masyarakat,
                petugas lapangan maupun sistem GPS.
            </p>
        </div>

        <button
            type="button"
            disabled
            class="rounded-2xl bg-bali-navy px-6 py-4 text-sm font-black text-white opacity-50 cursor-not-allowed"
        >
            + Tambah Laporan
        </button>
    </div>

    {{-- SUMMARY --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-500">Total Laporan</div>
            <div class="mt-3 text-4xl font-black text-bali-navy">{{ $totalReports }}</div>
            <div class="mt-2 text-xs text-slate-500">Seluruh laporan kejadian</div>
        </div>

        <div class="rounded-3xl border border-red-200 bg-red-50 p-6 shadow-sm">
            <div class="text-sm font-semibold text-red-600">Baru</div>
            <div class="mt-3 text-4xl font-black text-red-600">{{ $newReports }}</div>
            <div class="mt-2 text-xs text-red-500">Belum ditindaklanjuti</div>
        </div>

        <div class="rounded-3xl border border-amber-200 bg-amber-50 p-6 shadow-sm">
            <div class="text-sm font-semibold text-amber-700">Diproses</div>
            <div class="mt-3 text-4xl font-black text-amber-700">{{ $processingReports }}</div>
            <div class="mt-2 text-xs text-amber-600">Sedang ditangani admin</div>
        </div>

        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
            <div class="text-sm font-semibold text-emerald-700">Selesai</div>
            <div class="mt-3 text-4xl font-black text-emerald-700">{{ $completedReports }}</div>
            <div class="mt-2 text-xs text-emerald-600">Telah diselesaikan</div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="grid gap-6 xl:grid-cols-[1fr_320px]">

        {{-- TABLE --}}
        <section class="overflow-hidden rounded-3xl bg-white shadow-sm">
            <div class="border-b border-slate-100 p-6">
                <h3 class="text-xl font-black text-bali-ink">Daftar Laporan</h3>
                <p class="mt-2 text-sm text-slate-500">Riwayat seluruh laporan yang diterima sistem.</p>
            </div>

            <div>
                @forelse ($reports as $report)
                    <div class="flex flex-col gap-4 border-b border-slate-100 p-6 last:border-b-0 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-black text-bali-navy">{{ $report['code'] }}</span>
                                <span class="rounded-full px-3 py-1 text-xs font-black {{ $statusColors[$report['status']] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $report['status'] }}
                                </span>
                                <span class="rounded-full px-3 py-1 text-xs font-black {{ $priorityColors[$report['priority']] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $report['priority'] }}
                                </span>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-bali-ink">
                                {{ $report['category'] }} &middot; {{ $report['location'] }}
                            </p>
                            <p class="mt-1 text-xs text-slate-500">
                                Pelapor: {{ $report['reporter_name'] }} &middot; Penyewa: {{ $report['renter_name'] }}
                            </p>
                            <p class="mt-1 text-xs text-slate-400">{{ $report['reported_at'] }}</p>
                        </div>

                        <a href="{{ route('admin.incident-reports.show', $report['id']) }}"
                           class="rounded-full bg-bali-navy px-5 py-2 text-center text-sm font-black text-white transition hover:bg-bali-slate">
                            Detail
                        </a>
                    </div>
                @empty
                    <div class="p-8 text-center text-bali-muted">
                        Belum ada laporan.
                    </div>
                @endforelse
            </div>
        </section>

        {{-- SIDEBAR --}}
        <aside class="space-y-6">

            {{-- DISTRIBUSI PRIORITAS --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-black text-bali-ink">Distribusi Prioritas</h3>
                <div class="mt-6 space-y-5">
                    <div>
                        <div class="mb-2 flex justify-between text-sm">
                            <span>High</span>
                            <strong>1</strong>
                        </div>
                        <div class="h-2 rounded-full bg-slate-200">
                            <div class="h-2 w-1/5 rounded-full bg-red-500"></div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-2 flex justify-between text-sm">
                            <span>Medium</span>
                            <strong>2</strong>
                        </div>
                        <div class="h-2 rounded-full bg-slate-200">
                            <div class="h-2 w-2/5 rounded-full bg-amber-500"></div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-2 flex justify-between text-sm">
                            <span>Low</span>
                            <strong>2</strong>
                        </div>
                        <div class="h-2 rounded-full bg-slate-200">
                            <div class="h-2 w-2/5 rounded-full bg-emerald-500"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- JENIS LAPORAN --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-black text-bali-ink">Jenis Pelanggaran</h3>
                <div class="mt-5 space-y-4 text-sm">
                    <div class="flex justify-between"><span>Ugal-ugalan</span><strong>1</strong></div>
                    <div class="flex justify-between"><span>Lampu Merah</span><strong>1</strong></div>
                    <div class="flex justify-between"><span>Keluar Area</span><strong>1</strong></div>
                    <div class="flex justify-between"><span>Parkir</span><strong>1</strong></div>
                    <div class="flex justify-between"><span>Knalpot</span><strong>1</strong></div>
                </div>
            </div>

            {{-- LOKASI --}}
            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <h3 class="text-lg font-black text-bali-ink">Lokasi Terbanyak</h3>
                <div class="mt-5 space-y-4 text-sm">
                    <div class="flex justify-between"><span>Kuta</span><strong>1</strong></div>
                    <div class="flex justify-between"><span>Denpasar</span><strong>1</strong></div>
                    <div class="flex justify-between"><span>Tabanan</span><strong>1</strong></div>
                    <div class="flex justify-between"><span>Seminyak</span><strong>1</strong></div>
                    <div class="flex justify-between"><span>Canggu</span><strong>1</strong></div>
                </div>
            </div>

        </aside>

    </div>

</div>
@endsection