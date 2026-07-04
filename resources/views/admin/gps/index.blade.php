@extends('layouts.admin')

@section('page-title', 'GPS Tracking')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <div class="text-sm font-bold uppercase tracking-[0.18em] text-bali-teal">
                Live Vehicle Monitoring
            </div>

            <h2 class="mt-2 text-3xl font-black text-bali-ink">
                GPS Tracking Kendaraan
            </h2>

            <p class="mt-2 max-w-3xl text-sm text-bali-muted leading-7">
                Halaman ini merupakan simulasi GPS Tracking kendaraan yang sedang
                disewa pelanggan. Posisi kendaraan akan berubah secara otomatis
                untuk memberikan gambaran bagaimana sistem monitoring bekerja.
            </p>
        </div>

        <div class="rounded-3xl bg-white p-5 shadow-sm">

            <div class="text-xs font-bold uppercase tracking-wide text-slate-400">
                Status Sistem
            </div>

            <div class="mt-3 flex items-center gap-3">

                <div class="h-3 w-3 rounded-full bg-emerald-500"></div>

                <div class="font-bold text-emerald-600">
                    GPS Service Online
                </div>

            </div>

            <div class="mt-2 text-xs text-slate-500">
                Last Update :
                <span id="lastUpdateHeader">
                    baru saja
                </span>
            </div>

        </div>

    </div>

    {{-- SUMMARY --}}
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-500">
                Total Kendaraan Aktif
            </div>

            <div class="mt-3 text-4xl font-black text-bali-navy">
                5
            </div>

            <div class="mt-2 text-xs text-slate-500">
                Unit yang sedang dipantau GPS
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-500">
                Kendaraan Disewa
            </div>

            <div class="mt-3 text-4xl font-black text-emerald-600">
                4
            </div>

            <div class="mt-2 text-xs text-slate-500">
                Sedang digunakan pelanggan
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-500">
                Unit Offline
            </div>

            <div class="mt-3 text-4xl font-black text-red-600">
                1
            </div>

            <div class="mt-2 text-xs text-slate-500">
                GPS tidak mengirim data
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="text-sm font-semibold text-slate-500">
                Last Update
            </div>

            <div id="lastUpdate"
                class="mt-3 text-2xl font-black text-bali-teal">
                --
            </div>

            <div class="mt-2 text-xs text-slate-500">
                Waktu sinkronisasi terakhir
            </div>
        </div>

    </div>

    @php
    $markers = [
    [
        'unit' => 'Yamaha NMAX',
        'plate' => 'DK 1234 AA',
        'customer' => 'Andi Saputra',
        'location' => 'Kuta',
        'speed' => '0 km/jam',
        'updated_at' => '19:32',
        'status' => 'Aktif',
        'top' => '38%',
        'left' => '56%',
        'color' => 'bg-emerald-500',
    ],

    [
        'unit' => 'Honda PCX',
        'plate' => 'DK 5512 BB',
        'customer' => 'Budi Santoso',
        'location' => 'Denpasar',
        'speed' => '0 km/jam',
        'updated_at' => '19:28',
        'status' => 'Aktif',
        'top' => '46%',
        'left' => '63%',
        'color' => 'bg-emerald-500',
    ],

    [
        'unit' => 'Honda Vario',
        'plate' => 'DK 8821 CC',
        'customer' => 'Dewi Lestari',
        'location' => 'Seminyak',
        'speed' => '0 km/jam',
        'updated_at' => '19:26',
        'status' => 'Aktif',
        'top' => '53%',
        'left' => '44%',
        'color' => 'bg-emerald-500',
    ],

    [
        'unit' => 'Yamaha Aerox',
        'plate' => 'DK 7712 DD',
        'customer' => '-',
        'location' => 'Offline',
        'speed' => '-',
        'updated_at' => '18:55',
        'status' => 'Offline',
        'top' => '29%',
        'left' => '69%',
        'color' => 'bg-red-500',
    ],

    ];

    @endphp

    {{-- CONTENT: map + sidebar berdampingan --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- MAP --}}
        <section class="overflow-hidden rounded-3xl bg-white shadow-sm lg:col-span-2">

            <div class="border-b border-slate-100 p-6">

                <h3 class="text-xl font-black text-bali-ink">
                    Peta Monitoring Bali
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Simulasi posisi kendaraan berdasarkan lokasi GPS terakhir.
                </p>

            </div>

            <div class="relative">

                <img
                    src="{{ asset('images/bali-map.png') }}"
                    alt="Peta Bali"
                    class="block w-full"
                >

                @foreach($markers as $marker)

                    <div
                        class="absolute -translate-x-1/2 -translate-y-1/2"
                        style="
                            top: {{ $marker['top'] }};
                            left: {{ $marker['left'] }};
                        "
                    >

                        <div class="relative group">

                            {{-- PIN --}}
                            <div class="flex h-5 w-5 items-center justify-center rounded-full border-2 border-white shadow-lg {{ $marker['color'] }}">

                                <div class="h-2 w-2 rounded-full bg-white"></div>

                            </div>

                            {{-- POPUP --}}
                            <div
                                class="absolute bottom-7 left-1/2 hidden w-64 -translate-x-1/2 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl group-hover:block z-10"
                            >

                                <div class="text-base font-black text-bali-ink">
                                    {{ $marker['unit'] }}
                                </div>

                                <div class="mt-1 text-sm text-slate-500">
                                    {{ $marker['plate'] }}
                                </div>

                                <hr class="my-3">

                                <div class="space-y-2 text-sm">

                                    <div class="flex justify-between">
                                        <span>Penyewa</span>
                                        <strong>{{ $marker['customer'] }}</strong>
                                    </div>

                                    <div class="flex justify-between">
                                        <span>Lokasi</span>
                                        <strong>{{ $marker['location'] }}</strong>
                                    </div>

                                    <div class="flex justify-between">
                                        <span>Status</span>

                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-bold
                                            {{ $marker['status']=='Offline'
                                                ? 'bg-red-100 text-red-600'
                                                : 'bg-emerald-100 text-emerald-700'
                                            }}"
                                        >
                                            {{ $marker['status'] }}
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </section>

        {{-- SIDEBAR --}}
        <aside class="rounded-3xl bg-white shadow-sm lg:col-span-1">

            <div class="border-b border-slate-100 p-6">

                <h3 class="text-xl font-black text-bali-ink">
                    Kendaraan Aktif
                </h3>

                <p class="mt-2 text-sm text-slate-500">
                    Monitoring kendaraan yang sedang berada di lapangan.
                </p>

            </div>

            <div class="max-h-[700px] overflow-y-auto">

                @foreach($markers as $marker)

                    <div class="border-b border-slate-100 p-5 last:border-0">

                        <div class="flex items-start justify-between">

                            <div>

                                <div class="text-lg font-black text-bali-ink">
                                    {{ $marker['unit'] }}
                                </div>

                                <div class="text-sm text-slate-500">
                                    {{ $marker['plate'] }}
                                </div>

                            </div>

                            <span
                                class="rounded-full px-3 py-1 text-xs font-bold
                                {{ $marker['status']=='Offline'
                                    ? 'bg-red-100 text-red-600'
                                    : 'bg-emerald-100 text-emerald-700'
                                }}"
                            >
                                {{ $marker['status'] }}
                            </span>

                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-4 text-sm">

                            <div>

                                <div class="text-slate-400">
                                    Penyewa
                                </div>

                                <div class="mt-1 font-semibold text-bali-ink">
                                    {{ $marker['customer'] }}
                                </div>

                            </div>

                            <div>

                                <div class="text-slate-400">
                                    Lokasi
                                </div>

                                <div class="mt-1 font-semibold text-bali-ink">
                                    {{ $marker['location'] }}
                                </div>

                            </div>

                            <div>

                                <div class="text-slate-400">
                                    Kecepatan
                                </div>

                                <div class="mt-1 font-semibold text-bali-ink">
                                    {{ $marker['speed'] }}
                                </div>

                            </div>

                            <div>

                                <div class="text-slate-400">
                                    Last Update
                                </div>

                                <div class="mt-1 font-semibold text-bali-ink">
                                    {{ $marker['updated_at'] }}
                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </aside>

    </div>
</div>
@endsection