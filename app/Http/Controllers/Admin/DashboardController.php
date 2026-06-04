<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Motorcycle;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'pending_bookings' => Booking::where('status', Booking::STATUS_PENDING_APPROVAL)->count(),
            'active_bookings' => Booking::whereNotIn('status', Booking::finalStatuses())->count(),
            'completed_bookings' => Booking::where('status', Booking::STATUS_COMPLETED)->count(),
            'available_motorcycles' => Motorcycle::where('status', 'available')->count(),
            'customers' => User::where('role', 'customer')->count(),
        ];

        $latestBookings = Booking::with(['user', 'motorcycle'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestBookings'));
    }
}