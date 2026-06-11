<section class="relative overflow-hidden bg-bali-navy text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_10%_10%,rgba(13,148,136,0.36),transparent_28rem),radial-gradient(circle_at_86%_8%,rgba(249,115,22,0.30),transparent_30rem)]"></div>
    <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-bali-soft to-transparent"></div>

    <div class="container-page relative grid min-h-[720px] items-center gap-12 py-20 lg:grid-cols-[1.05fr_0.95fr]">
        <div>
            <span class="badge-teal bg-white/10 text-teal-100">Safe Motor Rental Bali</span>
            <h1 class="mt-6 max-w-4xl text-5xl font-black leading-[0.95] tracking-[-0.06em] md:text-7xl">
                Ride Bali safely with a clearer rental flow.
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">
                Pilih motor, ajukan sewa, setujui aturan keselamatan, upload pembayaran, dan pantau status dari satu sistem.
            </p>

            <div class="mt-9 flex flex-wrap gap-4">
                <a href="{{ route('motorcycles.index') }}" class="btn-primary">Cari Motor</a>
                <a href="#safety" class="btn-light">Lihat Safety Flow</a>
            </div>

            <div class="mt-10 grid max-w-2xl gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur"><strong class="block text-2xl font-black">Terms</strong><span class="mt-1 block text-sm text-slate-300">Wajib disetujui</span></div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur"><strong class="block text-2xl font-black">Checklist</strong><span class="mt-1 block text-sm text-slate-300">Sebelum serah-terima</span></div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-5 backdrop-blur"><strong class="block text-2xl font-black">Score</strong><span class="mt-1 block text-sm text-slate-300">Trusted Rider</span></div>
            </div>
        </div>

        <div class="glass-panel rounded-[2.25rem] p-6">
            <div class="overflow-hidden rounded-[1.8rem] bg-gradient-to-br from-slate-950 via-slate-800 to-teal-900 p-7">
                <div class="flex items-center justify-between gap-4">
                    <div><span class="text-xs font-black uppercase tracking-[0.18em] text-teal-200">Featured Unit</span><h3 class="mt-2 text-2xl font-black">Ready for Bali routes</h3></div>
                    <span class="rounded-full bg-white px-4 py-2 text-xs font-black text-bali-navy">Available</span>
                </div>
                <div class="mt-10 flex h-72 items-center justify-center rounded-[1.5rem] bg-white/95 p-8 text-center text-2xl font-black text-bali-navy">
                    @if ($motorcycles->first()?->image)
                        <img src="{{ asset('storage/' . $motorcycles->first()->image) }}" alt="Featured motorcycle" class="h-full w-full object-contain">
                    @else
                        BaliMotor Rental
                    @endif
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white p-5 text-bali-navy"><span class="text-xs font-bold text-bali-muted">Mulai dari</span><strong class="mt-1 block text-xl font-black">Rp{{ number_format($motorcycles->first()?->price_per_day ?? 80000, 0, ',', '.') }} / hari</strong></div>
                    <div class="rounded-2xl bg-white/10 p-5"><span class="text-xs font-bold text-slate-300">Termasuk</span><strong class="mt-1 block text-xl font-black">Helm + STNK</strong></div>
                </div>
            </div>
        </div>
    </div>
</section>
