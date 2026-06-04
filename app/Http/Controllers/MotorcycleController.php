<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Motorcycle;
use Illuminate\Http\Request;

class MotorcycleController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'type' => ['nullable', 'string', 'max:100'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'delivery_location' => ['nullable', 'string', 'max:255'],
        ]);

        $query = Motorcycle::query()
            ->where('status', 'available');

        if ($request->filled('type')) {
            $query->where('type', $validated['type']);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDoesntHave('bookings', function ($bookingQuery) use ($validated) {
                $bookingQuery
                    ->whereIn('status', Booking::blockingStatuses())
                    ->whereDate('start_date', '<=', $validated['end_date'])
                    ->whereDate('end_date', '>=', $validated['start_date']);
            });
        }

        $motorcycles = $query
            ->orderBy('brand')
            ->orderBy('model')
            ->get();

        $types = Motorcycle::where('status', 'available')
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        return view('motorcycles.index', compact('motorcycles', 'types'));
    }

    public function show(Motorcycle $motorcycle)
    {
        abort_unless($motorcycle->status === 'available', 404);

        return view('motorcycles.show', compact('motorcycle'));
    }
}