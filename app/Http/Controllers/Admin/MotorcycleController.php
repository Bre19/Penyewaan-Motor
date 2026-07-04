<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MotorcycleController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(array_keys(Motorcycle::statusLabels()))],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Motorcycle::with('stocks')->latest();

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['search'])) {
            $search = $validated['search'];

            $query->where(function ($q) use ($search) {
                $q->where('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('plate_number', 'like', "%{$search}%");
            });
        }

        $motorcycles = $query->paginate(10)->withQueryString();

        return view('admin.motorcycles.index', [
            'motorcycles' => $motorcycles,
            'statusLabels' => Motorcycle::statusLabels(),
        ]);
    }

    public function create()
    {
        return view('admin.motorcycles.create', [
            'motorcycle' => new Motorcycle(),
            'statusLabels' => Motorcycle::statusLabels(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:' . (now()->year + 1)],
            'plate_number' => ['required', 'string', 'max:30', 'unique:motorcycles,plate_number'],
            'price_per_day' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'status' => ['required', Rule::in(array_keys(Motorcycle::statusLabels()))],
            'description' => ['nullable', 'string', 'max:3000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $validated) {

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('motorcycles', 'public');
            }

            Motorcycle::create($validated);
        });

        return redirect()
            ->route('admin.motorcycles.index')
            ->with('success', 'Motor berhasil ditambahkan.');
    }

    public function edit(Motorcycle $motorcycle)
    {
        return view('admin.motorcycles.edit', [
            'motorcycle' => $motorcycle,
            'statusLabels' => Motorcycle::statusLabels(),
        ]);
    }

    public function update(Request $request, Motorcycle $motorcycle)
    {
        $validated = $request->validate([
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:' . (now()->year + 1)],
            'plate_number' => [
                'required',
                'string',
                'max:30',
                Rule::unique('motorcycles', 'plate_number')->ignore($motorcycle->id)
            ],
            'price_per_day' => ['required', 'numeric', 'min:0', 'max:999999999'],
            'status' => ['required', Rule::in(array_keys(Motorcycle::statusLabels()))],
            'description' => ['nullable', 'string', 'max:3000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        DB::transaction(function () use ($request, $motorcycle, $validated) {

            if ($request->hasFile('image')) {

                // delete old image
                if ($motorcycle->image) {
                    Storage::disk('public')->delete($motorcycle->image);
                }

                // store new
                $validated['image'] = $request->file('image')->store('motorcycles', 'public');
            }

            $motorcycle->update($validated);
        });

        return redirect()
            ->route('admin.motorcycles.index')
            ->with('success', 'Data motor berhasil diperbarui.');
    }

    public function destroy(Motorcycle $motorcycle)
    {
        if ($motorcycle->bookings()->exists()) {
            return back()->with('error', 'Motor masih digunakan dalam booking.');
        }

        DB::transaction(function () use ($motorcycle) {

            if ($motorcycle->image) {
                Storage::disk('public')->delete($motorcycle->image);
            }

            $motorcycle->delete();
        });

        return redirect()
            ->route('admin.motorcycles.index')
            ->with('success', 'Motor berhasil dihapus.');
    }
}