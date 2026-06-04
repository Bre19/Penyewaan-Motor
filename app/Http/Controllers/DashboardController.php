<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $activeBooking = Booking::with('motorcycle')
            ->where('user_id', $user->id)
            ->whereNotIn('status', Booking::finalStatuses())
            ->latest()
            ->first();

        $latestBookings = Booking::with('motorcycle')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $bookingStats = [
            'total' => Booking::where('user_id', $user->id)->count(),
            'active' => Booking::where('user_id', $user->id)
                ->whereNotIn('status', Booking::finalStatuses())
                ->count(),
            'completed' => Booking::where('user_id', $user->id)
                ->where('status', Booking::STATUS_COMPLETED)
                ->count(),
        ];

        $profileCompleted = filled($user->phone_number)
            && filled($user->current_address)
            && filled($user->passport_number)
            && filled($user->origin_country);

        return view('dashboard', compact(
            'user',
            'activeBooking',
            'latestBookings',
            'bookingStats',
            'profileCompleted'
        ));
    }
}