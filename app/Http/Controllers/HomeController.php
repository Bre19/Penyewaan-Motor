<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;

class HomeController extends Controller
{
    public function index()
    {
        $motorcycles = Motorcycle::where('status', 'available')
            ->latest()
            ->take(4)
            ->get();

        $types = Motorcycle::where('status', 'available')
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->pluck('type');

        return view('home', compact('motorcycles', 'types'));
    }
}