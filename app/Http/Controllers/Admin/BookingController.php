<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(array_keys(Booking::statusLabels()))],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Booking::with(['user', 'motorcycle'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $validated['status']);
        }

        if ($request->filled('search')) {
            $search = $validated['search'];

            $query->where(function ($bookingQuery) use ($search) {
                $bookingQuery
                    ->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('motorcycle', function ($motorcycleQuery) use ($search) {
                        $motorcycleQuery
                            ->where('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%")
                            ->orWhere('plate_number', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->paginate(10)->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'statusLabels' => Booking::statusLabels(),
        ]);
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'motorcycle']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        if ($booking->status !== Booking::STATUS_PENDING_APPROVAL) {
            return back()->with('error', 'Booking ini tidak berada pada status menunggu persetujuan.');
        }

        $booking->update([
            'status' => Booking::STATUS_APPROVED,
            'approved_at' => now(),
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Booking berhasil disetujui.');
    }

    public function reject(Request $request, Booking $booking)
    {
        if ($booking->status !== Booking::STATUS_PENDING_APPROVAL) {
            return back()->with('error', 'Booking ini tidak berada pada status menunggu persetujuan.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $booking->update([
            'status' => Booking::STATUS_REJECTED,
            'rejected_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Booking berhasil ditolak.');
    }
}