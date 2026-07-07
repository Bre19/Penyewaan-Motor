@extends('layouts.admin')

@section('page-title', 'Detail Laporan Insiden')

@section('content')
@php
    // DUMMY DATA — replace with real lookup once _dummy.blade.php schema / controller is available.
    // $id is auto-injected by Route::view('/incident-reports/{id}', ...).
    $incidentCode = 'IR-2026-' . str_pad((int) $id, 3, '0', STR_PAD_LEFT);

    $incident = [
        'code' => $incidentCode,
        'status' => 'Diproses', // Baru | Diproses | Selesai — aligned with index.blade.php
        'priority' => 'High',   // Low | Medium | High | Critical
        'reported_at' => '20 Juni 2026, 15:30',
        'reported_by' => 'Made Sudarma',
        'report_source' => 'Citizen',
        'severity_percent' => 80,
        'severity_label' => 'High Risk',
        'confidence_score' => 94,
        'related_booking' => [
            'code' => 'BK-2026-001',
            'status' => 'Completed',
            'date' => '20 Juni 2026',
        ],
        'reporter' => [
            'name' => 'Made Sudarma',
            'type' => 'Masyarakat',
            'phone' => '0812xxxxxxx',
            'email' => null,
        ],
        'renter' => [
            'name' => 'John Smith',
            'nationality' => 'Australia',
            'booking_code' => 'BK-2026-001',
            'motorcycle' => 'Honda PCX',
            'unit_code' => 'PCX-004',
            'plate_number' => 'DK 1234 AB',
        ],
        'location' => [
            'address' => 'Jl. Sunset Road, Kuta, Bali',
            'lat' => -8.6754,
            'lng' => 115.2204,
            'date' => '20 Juni 2026',
            'time' => '15:24',
        ],
        'chronology' => [
            'Penyewa terlihat mengendarai kendaraan dengan kecepatan tinggi.',
            'Melawan arus di jalur satu arah.',
            'Hampir menabrak kendaraan lain dari arah berlawanan.',
            'Laporan diterima dari masyarakat sekitar lokasi.',
        ],
        'driver_behavior_tags' => ['Overspeed', 'Wrong Way', 'Aggressive Driving'],
        'evidence' => [
            ['type' => 'photo', 'label' => 'Foto 1'],
            ['type' => 'photo', 'label' => 'Foto 2'],
            ['type' => 'photo', 'label' => 'Foto 3'],
            ['type' => 'video', 'label' => 'Video'],
            ['type' => 'gps', 'label' => 'Screenshot GPS'],
        ],
        'ai_analysis' => [
            'driving_behavior' => 'Unsafe',
            'estimated_risk' => 'High',
            'recommendation' => 'Perlu dilakukan investigasi lebih lanjut terhadap penyewa.',
        ],
        'renter_history' => [
            'total_rental' => 6,
            'incident_count' => 1,
            'trusted_rider' => false,
            'safety_score' => 72,
            'average_rating' => 4.5,
        ],
        'timeline' => [
            ['time' => '13:20', 'event' => 'Laporan dibuat oleh pelapor'],
            ['time' => '13:35', 'event' => 'Admin menerima dan meninjau laporan'],
            ['time' => '14:10', 'event' => 'Investigasi dimulai'],
            ['time' => '16:20', 'event' => 'Status ditutup (Selesai)'],
        ],
        'admin_notes' => "Admin telah menghubungi penyewa.\nTidak ditemukan kerusakan kendaraan.\nKasus ditutup.",
        'resolution' => [
            'resolved' => true,
            'by' => 'Admin',
            'date' => '20 Juni 2026',
        ],
    ];

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

    $severityBarColor = match (true) {
        $incident['severity_percent'] >= 75 => 'bg-red-500',
        $incident['severity_percent'] >= 50 => 'bg-orange-500',
        $incident['severity_percent'] >= 25 => 'bg-amber-500',
        default => 'bg-teal-500',
    };
@endphp

<div class="mb-6">
    <a href="{{ route('admin.incident-reports.index') }}"
       class="inline-flex items-center gap-2 text-sm font-bold text-bali-muted hover:text-bali-navy">
        &larr; Kembali ke Daftar Laporan
    </a>
</div>

{{-- HEADER --}}
<div class="rounded-[1.8rem] border border-bali-line bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <span class="badge-orange">Incident Report</span>
            <h1 class="mt-3 text-3xl font-black text-bali-navy">{{ $incident['code'] }}</h1>
            <div class="mt-3 flex flex-wrap gap-2">
                <span class="rounded-full px-4 py-1.5 text-xs font-black {{ $statusColors[$incident['status']] ?? 'bg-slate-100 text-slate-700' }}">
                    {{ $incident['status'] }}
                </span>
                <span class="rounded-full px-4 py-1.5 text-xs font-black {{ $priorityColors[$incident['priority']] ?? 'bg-slate-100 text-slate-700' }}">
                    Priority: {{ $incident['priority'] }}
                </span>
            </div>
        </div>

        <div class="text-sm text-bali-muted lg:text-right">
            <div>Dilaporkan: <strong class="text-bali-navy">{{ $incident['reported_at'] }}</strong></div>
            <div class="mt-1">Oleh: <strong class="text-bali-navy">{{ $incident['reported_by'] }}</strong></div>
            <div class="mt-1">Sumber: <strong class="text-bali-navy">{{ $incident['report_source'] }}</strong></div>
        </div>
    </div>
</div>

{{-- QUICK STATS --}}
<div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Incident Severity</span>
        <div class="mt-3 h-3 w-full overflow-hidden rounded-full bg-slate-100">
            <div class="h-full {{ $severityBarColor }}" style="width: {{ $incident['severity_percent'] }}%"></div>
        </div>
        <div class="mt-2 flex items-center justify-between">
            <strong class="text-xl font-black text-bali-navy">{{ $incident['severity_percent'] }}%</strong>
            <span class="text-xs font-bold text-bali-muted">{{ $incident['severity_label'] }}</span>
        </div>
    </div>

    <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">System Confidence</span>
        <strong class="mt-3 block text-3xl font-black text-bali-navy">{{ $incident['confidence_score'] }}%</strong>
        <p class="mt-1 text-xs text-bali-muted">Berdasarkan analisis data GPS &amp; sensor.</p>
    </div>

    <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Report Source</span>
        <strong class="mt-3 block text-xl font-black text-bali-navy">{{ $incident['report_source'] }}</strong>
        <p class="mt-1 text-xs text-bali-muted">Sumber informasi awal laporan.</p>
    </div>

    <div class="rounded-[1.7rem] border border-bali-line bg-white p-6 shadow-sm">
        <span class="text-sm font-bold text-bali-muted">Related Booking</span>
        <strong class="mt-3 block text-xl font-black text-bali-navy">{{ $incident['related_booking']['code'] }}</strong>
        <p class="mt-1 text-xs text-bali-muted">
            {{ $incident['related_booking']['status'] }} &middot; {{ $incident['related_booking']['date'] }}
        </p>
    </div>
</div>

{{-- MAIN CONTENT --}}
<div class="mt-6 grid gap-6 lg:grid-cols-[1fr_0.75fr]">
    <div class="space-y-6">

        {{-- INFORMASI PELAPOR --}}
        <div class="surface-card rounded-[1.8rem] p-6">
            <h2 class="text-lg font-black text-bali-navy">Informasi Pelapor</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div><span class="block text-xs font-bold uppercase text-slate-500">Nama Pelapor</span><strong class="mt-1 block text-bali-navy">{{ $incident['reporter']['name'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Jenis Pelapor</span><strong class="mt-1 block text-bali-navy">{{ $incident['reporter']['type'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Nomor HP</span><strong class="mt-1 block text-bali-navy">{{ $incident['reporter']['phone'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Email</span><strong class="mt-1 block text-bali-navy">{{ $incident['reporter']['email'] ?? '-' }}</strong></div>
            </div>
        </div>

        {{-- INFORMASI PENYEWA --}}
        <div class="surface-card rounded-[1.8rem] p-6">
            <h2 class="text-lg font-black text-bali-navy">Informasi Penyewa</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div><span class="block text-xs font-bold uppercase text-slate-500">Nama</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter']['name'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Nationality</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter']['nationality'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Booking</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter']['booking_code'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Motor</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter']['motorcycle'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Unit</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter']['unit_code'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Plat</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter']['plate_number'] }}</strong></div>
            </div>
        </div>

        {{-- LOKASI KEJADIAN --}}
        <div class="surface-card rounded-[1.8rem] p-6">
            <h2 class="text-lg font-black text-bali-navy">Lokasi Kejadian</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div><span class="block text-xs font-bold uppercase text-slate-500">Lokasi</span><strong class="mt-1 block text-bali-navy">{{ $incident['location']['address'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Koordinat</span><strong class="mt-1 block text-bali-navy">{{ $incident['location']['lat'] }}, {{ $incident['location']['lng'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Tanggal</span><strong class="mt-1 block text-bali-navy">{{ $incident['location']['date'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Jam</span><strong class="mt-1 block text-bali-navy">{{ $incident['location']['time'] }}</strong></div>
            </div>
            <div class="mt-5 flex h-56 items-center justify-center rounded-2xl border border-dashed border-bali-line bg-slate-50 text-sm font-bold text-slate-400">
                MAP PLACEHOLDER
            </div>
        </div>

        {{-- KRONOLOGI --}}
        <div class="surface-card rounded-[1.8rem] p-6">
            <h2 class="text-lg font-black text-bali-navy">Kronologi</h2>
            <ul class="mt-4 space-y-2 text-sm leading-6 text-bali-muted">
                @foreach ($incident['chronology'] as $line)
                    <li>&bull; {{ $line }}</li>
                @endforeach
            </ul>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($incident['driver_behavior_tags'] as $tag)
                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-700">{{ $tag }}</span>
                @endforeach
            </div>
        </div>

        {{-- BUKTI PENDUKUNG --}}
        <div class="surface-card rounded-[1.8rem] p-6">
            <h2 class="text-lg font-black text-bali-navy">Bukti Pendukung</h2>
            <div class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-3">
                @foreach ($incident['evidence'] as $item)
                    <div>
                        <img
                            src="https://placehold.co/300x200?text={{ urlencode($item['label']) }}"
                            class="aspect-square w-full rounded-2xl border border-bali-line object-cover"
                            alt="{{ $item['label'] }}"
                        >
                        <span class="mt-2 block text-center text-xs font-bold text-bali-muted">{{ $item['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ANALISIS SISTEM --}}
        <div class="surface-card rounded-[1.8rem] p-6">
            <span class="badge-teal">AI Risk Assessment</span>
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div><span class="block text-xs font-bold uppercase text-slate-500">Driving Behavior</span><strong class="mt-1 block text-bali-navy">{{ $incident['ai_analysis']['driving_behavior'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Estimated Risk</span><strong class="mt-1 block text-bali-navy">{{ $incident['ai_analysis']['estimated_risk'] }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">Recommendation</span><strong class="mt-1 block text-bali-navy">{{ $incident['ai_analysis']['recommendation'] }}</strong></div>
            </div>
        </div>

        {{-- TIMELINE --}}
        <div class="surface-card rounded-[1.8rem] p-6">
            <h2 class="text-lg font-black text-bali-navy">Timeline</h2>
            <div class="mt-5 space-y-6 border-l-2 border-bali-line pl-6">
                @foreach ($incident['timeline'] as $event)
                    <div class="relative">
                        <span class="absolute -left-[29px] top-1 h-3 w-3 rounded-full bg-bali-teal"></span>
                        <span class="text-xs font-black uppercase tracking-wide text-bali-teal">{{ $event['time'] }}</span>
                        <p class="mt-1 text-sm font-semibold text-bali-navy">{{ $event['event'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- CATATAN ADMIN --}}
        <div class="surface-card rounded-[1.8rem] p-6">
            <h2 class="text-lg font-black text-bali-navy">Catatan Admin</h2>
            <textarea
                readonly
                rows="4"
                class="mt-4 w-full rounded-2xl border border-bali-line bg-slate-50 p-4 text-sm text-bali-muted"
            >{{ $incident['admin_notes'] }}</textarea>
        </div>
    </div>

    {{-- ASIDE --}}
    <aside class="space-y-6 lg:sticky lg:top-6 lg:self-start">
        <div class="surface-card rounded-[1.8rem] p-6">
            <h2 class="text-lg font-black text-bali-navy">Action</h2>
            <div class="mt-4 flex flex-col gap-3">
                <a href="{{ route('admin.incident-reports.index') }}" class="btn-light text-center">Back</a>
                <button type="button" onclick="window.print()" class="btn-dark">Print Report</button>
                <button type="button" disabled class="btn-primary opacity-50 cursor-not-allowed">Download PDF</button>
                <button type="button" disabled class="btn-primary opacity-50 cursor-not-allowed">Export</button>
            </div>
        </div>

        <div class="surface-card rounded-[1.8rem] p-6">
            <h2 class="text-lg font-black text-bali-navy">Riwayat Penyewa</h2>
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="rounded-2xl bg-slate-100 p-4"><span class="block text-xs text-bali-muted">Total Rental</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter_history']['total_rental'] }}</strong></div>
                <div class="rounded-2xl bg-slate-100 p-4"><span class="block text-xs text-bali-muted">Incident</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter_history']['incident_count'] }}</strong></div>
                <div class="rounded-2xl bg-slate-100 p-4"><span class="block text-xs text-bali-muted">Trusted Rider</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter_history']['trusted_rider'] ? 'Yes' : 'No' }}</strong></div>
                <div class="rounded-2xl bg-slate-100 p-4"><span class="block text-xs text-bali-muted">Safety Score</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter_history']['safety_score'] }}</strong></div>
                <div class="col-span-2 rounded-2xl bg-slate-100 p-4"><span class="block text-xs text-bali-muted">Average Rating</span><strong class="mt-1 block text-bali-navy">{{ $incident['renter_history']['average_rating'] }}</strong></div>
            </div>
        </div>

        <div class="surface-card rounded-[1.8rem] p-6">
            <h2 class="text-lg font-black text-bali-navy">Resolution</h2>
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div><span class="block text-xs font-bold uppercase text-slate-500">Resolved</span><strong class="mt-1 block text-bali-navy">{{ $incident['resolution']['resolved'] ? 'Yes' : 'No' }}</strong></div>
                <div><span class="block text-xs font-bold uppercase text-slate-500">By</span><strong class="mt-1 block text-bali-navy">{{ $incident['resolution']['by'] }}</strong></div>
                <div class="col-span-2"><span class="block text-xs font-bold uppercase text-slate-500">Date</span><strong class="mt-1 block text-bali-navy">{{ $incident['resolution']['date'] }}</strong></div>
            </div>
        </div>
    </aside>
</div>
@endsection