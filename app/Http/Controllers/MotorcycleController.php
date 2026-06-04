<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use Illuminate\Http\Request;

class MotorcycleController extends Controller
{
    public function index(Request $request)
    {
        $query = Motorcycle::query()
            ->where('status', 'available');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
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