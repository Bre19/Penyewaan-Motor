<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Motorcycle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function create(Motorcycle $motorcycle)
    {
        abort_unless($motorcycle->status === 'available', 404);

        return view('bookings.create', compact('motorcycle'));
    }

    public function store(Request $request, Motorcycle $motorcycle)
    {
        abort_unless($motorcycle->status === 'available', 404);

        $validated = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'delivery_location' => ['required', 'string', 'max:255'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->startOfDay();

        if ($this->hasOverlappingBooking($motorcycle, $startDate, $endDate)) {
            throw ValidationException::withMessages([
                'start_date' => 'Motor ini sudah memiliki pengajuan atau penyewaan pada rentang tanggal tersebut.',
            ]);
        }

        $durationDays = $startDate->diffInDays($endDate) + 1;
        $pricePerDay = (float) $motorcycle->price_per_day;
        $totalPrice = $durationDays * $pricePerDay;

        $booking = Booking::create([
            'user_id' => $request->user()->id,
            'motorcycle_id' => $motorcycle->id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'duration_days' => $durationDays,
            'delivery_location' => $validated['delivery_location'],
            'customer_note' => $validated['customer_note'] ?? null,
            'price_per_day' => $pricePerDay,
            'total_price' => $totalPrice,
            'status' => Booking::STATUS_PENDING_APPROVAL,
        ]);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Pengajuan penyewaan berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function show(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['motorcycle', 'latestPayment']);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        if (! $booking->canBeCancelledByCustomer()) {
            return back()->with('error', 'Booking ini sudah tidak dapat dibatalkan oleh penyewa.');
        }

        $booking->update([
            'status' => Booking::STATUS_CANCELLED,
            'cancelled_at' => now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    private function hasOverlappingBooking(Motorcycle $motorcycle, Carbon $startDate, Carbon $endDate): bool
    {
        return Booking::where('motorcycle_id', $motorcycle->id)
            ->whereIn('status', Booking::blockingStatuses())
            ->whereDate('start_date', '<=', $endDate->toDateString())
            ->whereDate('end_date', '>=', $startDate->toDateString())
            ->exists();
    }
}