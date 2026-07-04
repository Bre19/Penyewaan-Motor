<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Motorcycle;
use App\Models\MotorcycleStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MotorcycleStockController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'motorcycle_id' => ['nullable', 'exists:motorcycles,id'],
            'status' => ['nullable', Rule::in(array_keys(MotorcycleStock::statusLabels()))],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = MotorcycleStock::with([
            'motorcycle',
            'bookings.user',
        ])->latest();

        if (!empty($validated['motorcycle_id'])) {
            $query->where('motorcycle_id', $validated['motorcycle_id']);
        }

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['search'])) {

            $search = $validated['search'];

            $query->where(function ($q) use ($search) {

                $q->where('stock_code', 'like', "%{$search}%")
                    ->orWhere('plate_number', 'like', "%{$search}%")
                    ->orWhereHas('motorcycle', function ($motorcycle) use ($search) {

                        $motorcycle
                            ->where('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%");

                    });

            });
        }

        $stocks = $query
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => MotorcycleStock::count(),
            'available' => MotorcycleStock::where('status', MotorcycleStock::STATUS_AVAILABLE)->count(),
            'booked' => MotorcycleStock::where('status', MotorcycleStock::STATUS_BOOKED)->count(),
            'rented' => MotorcycleStock::where('status', MotorcycleStock::STATUS_RENTED)->count(),
            'maintenance' => MotorcycleStock::where('status', MotorcycleStock::STATUS_MAINTENANCE)->count(),
            'inactive' => MotorcycleStock::where('status', MotorcycleStock::STATUS_INACTIVE)->count(),
        ];

        $dummyLocations = $this->generateDummyLocations($stocks->getCollection());

        return view('admin.motorcycle-stocks.index', [
            'stocks' => $stocks,
            'motorcycles' => Motorcycle::orderBy('brand')
                ->orderBy('model')
                ->get(),
            'statusLabels' => MotorcycleStock::statusLabels(),
            'stats' => $stats,
            'dummyLocations' => $dummyLocations,
        ]);
    }

    public function create()
    {
        return view('admin.motorcycle-stocks.create', [
            'stock' => new MotorcycleStock(),
            'motorcycles' => Motorcycle::orderBy('brand')->orderBy('model')->get(),
            'statusLabels' => MotorcycleStock::statusLabels(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'motorcycle_id' => ['required', 'exists:motorcycles,id'],
            'stock_code' => ['required', 'string', 'max:50', 'unique:motorcycle_stocks,stock_code'],
            'plate_number' => ['required', 'string', 'max:30', 'unique:motorcycle_stocks,plate_number'],
            'status' => ['required', Rule::in(array_keys(MotorcycleStock::statusLabels()))],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($request, $validated) {

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('motorcycle-stocks', 'public');
            }

            MotorcycleStock::create($validated);
        });

        return redirect()
            ->route('admin.motorcycle-stocks.index')
            ->with('success', 'Unit motor berhasil ditambahkan.');
    }

    public function edit(MotorcycleStock $motorcycleStock)
    {
        return view('admin.motorcycle-stocks.edit', [
            'stock' => $motorcycleStock,
            'motorcycles' => Motorcycle::orderBy('brand')->orderBy('model')->get(),
            'statusLabels' => MotorcycleStock::statusLabels(),
        ]);
    }

    public function update(Request $request, MotorcycleStock $motorcycleStock)
    {
        $validated = $request->validate([
            'motorcycle_id' => ['required', 'exists:motorcycles,id'],
            'stock_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('motorcycle_stocks', 'stock_code')->ignore($motorcycleStock->id),
            ],
            'plate_number' => [
                'required',
                'string',
                'max:30',
                Rule::unique('motorcycle_stocks', 'plate_number')->ignore($motorcycleStock->id),
            ],
            'status' => ['required', Rule::in(array_keys(MotorcycleStock::statusLabels()))],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($request, $motorcycleStock, $validated) {

            if ($request->hasFile('image')) {

                if ($motorcycleStock->image) {
                    Storage::disk('public')->delete($motorcycleStock->image);
                }

                $validated['image'] = $request->file('image')->store('motorcycle-stocks', 'public');
            }

            $motorcycleStock->update($validated);

            $motorcycleStock->syncStatus();
        });

        return redirect()
            ->route('admin.motorcycle-stocks.index')
            ->with('success', 'Data unit motor berhasil diperbarui.');
    }

    public function destroy(MotorcycleStock $motorcycleStock)
    {
        if (! $motorcycleStock->canBeDeleted()) {
            return back()->with(
                'error',
                'Unit motor sedang digunakan sehingga tidak dapat dihapus.'
            );
        }

        DB::transaction(function () use ($motorcycleStock) {

            if ($motorcycleStock->image) {
                Storage::disk('public')->delete($motorcycleStock->image);
            }

            $motorcycleStock->delete();
        });

        return redirect()
            ->route('admin.motorcycle-stocks.index')
            ->with('success', 'Unit motor berhasil dihapus.');
    }

    private function generateDummyLocations($stocks): array
    {
        $locations = [];

        $baliLocations = [

            ['name' => 'Denpasar', 'lat' => -8.6705, 'lng' => 115.2126],
            ['name' => 'Sanur', 'lat' => -8.6942, 'lng' => 115.2636],
            ['name' => 'Kuta', 'lat' => -8.7177, 'lng' => 115.1682],
            ['name' => 'Legian', 'lat' => -8.7069, 'lng' => 115.1695],
            ['name' => 'Seminyak', 'lat' => -8.6909, 'lng' => 115.1617],
            ['name' => 'Canggu', 'lat' => -8.6481, 'lng' => 115.1385],
            ['name' => 'Mengwi', 'lat' => -8.5349, 'lng' => 115.1755],
            ['name' => 'Tanah Lot', 'lat' => -8.6212, 'lng' => 115.0868],
            ['name' => 'Ubud', 'lat' => -8.5069, 'lng' => 115.2625],
            ['name' => 'Tegalalang', 'lat' => -8.4356, 'lng' => 115.2797],
            ['name' => 'Kintamani', 'lat' => -8.2478, 'lng' => 115.3783],
            ['name' => 'Bedugul', 'lat' => -8.2752, 'lng' => 115.1668],
            ['name' => 'Lovina', 'lat' => -8.1632, 'lng' => 115.0252],
            ['name' => 'Singaraja', 'lat' => -8.1120, 'lng' => 115.0881],
            ['name' => 'Amed', 'lat' => -8.3394, 'lng' => 115.6465],
            ['name' => 'Candidasa', 'lat' => -8.5037, 'lng' => 115.5705],
            ['name' => 'Padang Bai', 'lat' => -8.5312, 'lng' => 115.5110],
            ['name' => 'Nusa Dua', 'lat' => -8.8096, 'lng' => 115.2297],
            ['name' => 'Jimbaran', 'lat' => -8.7906, 'lng' => 115.1605],
            ['name' => 'Uluwatu', 'lat' => -8.8291, 'lng' => 115.0848],
            ['name' => 'GWK', 'lat' => -8.8105, 'lng' => 115.1677],
            ['name' => 'Pelabuhan Gilimanuk', 'lat' => -8.1592, 'lng' => 114.4362],
            ['name' => 'Negara', 'lat' => -8.3561, 'lng' => 114.6221],
            ['name' => 'Tabanan', 'lat' => -8.5413, 'lng' => 115.1252],
            ['name' => 'Bangli', 'lat' => -8.4543, 'lng' => 115.3545],
            ['name' => 'Klungkung', 'lat' => -8.5386, 'lng' => 115.4047],
            ['name' => 'Karangasem', 'lat' => -8.4506, 'lng' => 115.6168],
        ];

        foreach ($stocks as $stock) {

            if ($stock->status !== MotorcycleStock::STATUS_RENTED) {
                continue;
            }

            $seed = floor(now()->timestamp / (60 * 60 * 5));

            srand($stock->id + $seed);

            $base = $baliLocations[array_rand($baliLocations)];

            $lat = $base['lat'] + (rand(-150, 150) / 10000);
            $lng = $base['lng'] + (rand(-150, 150) / 10000);

            $locations[] = [
                'stock_id' => $stock->id,
                'stock_code' => $stock->stock_code,
                'plate_number' => $stock->plate_number,
                'customer' => optional(
                    $stock->bookings()
                        ->where('status', Booking::STATUS_ONGOING)
                        ->latest()
                        ->first()
                )->user?->name ?? '-',

                'motorcycle' =>
                    $stock->motorcycle->brand .
                    ' ' .
                    $stock->motorcycle->model,

                'location' => $base['name'],

                'latitude' => round($lat, 6),

                'longitude' => round($lng, 6),

                'updated_at' => now()
                    ->subMinutes(rand(2, 45))
                    ->format('d M Y H:i'),
            ];
        }

        return $locations;
    }
}