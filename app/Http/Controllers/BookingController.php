<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MotorcycleStock;
use App\Models\Motorcycle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function create(Motorcycle $motorcycle)
    {
        $motorcycle->loadCount([
            'stocks as available_stock_count' => function ($query) {
                $query->where('status', MotorcycleStock::STATUS_AVAILABLE);
            },
        ]);

        abort_if(
            $motorcycle->available_stock_count <= 0,
            404,
            'Unit motor tidak tersedia.'
        );

        return view('bookings.create', [
            'motorcycle' => $motorcycle,
        ]);
    }

    public function store(Request $request, Motorcycle $motorcycle)
    {
        $validated = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'delivery_location' => ['required', 'string', 'max:255'],
            'customer_note' => ['nullable', 'string', 'max:1000'],
            'terms_accepted' => ['accepted'],
        ], [
            'terms_accepted.accepted' => 'Anda harus menyetujui Terms & Condition sebelum mengajukan sewa.',
        ]);

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->startOfDay();

        return DB::transaction(function () use ($request, $motorcycle, $startDate, $endDate, $validated) {

            $motorcycle = Motorcycle::query()
                ->lockForUpdate()
                ->with('stocks')
                ->findOrFail($motorcycle->id);

            $availableStock = MotorcycleStock::query()
                ->where('motorcycle_id', $motorcycle->id)
                ->where('status', MotorcycleStock::STATUS_AVAILABLE)
                ->whereDoesntHave('bookings', function ($query) use ($startDate, $endDate) {

                    $query
                        ->whereIn('status', Booking::blockingStatuses())
                        ->whereDate('start_date', '<=', $endDate)
                        ->whereDate('end_date', '>=', $startDate);

                })
                ->orderBy('stock_code')
                ->lockForUpdate()
                ->first();

            if (! $availableStock) {
                throw ValidationException::withMessages([
                    'start_date' => 'Seluruh unit motor sedang tidak tersedia.',
                ]);
            }


            $durationDays = $startDate->diffInDays($endDate) + 1;
            $pricePerDay = (float) $motorcycle->price_per_day;
            $totalPrice = $durationDays * $pricePerDay;

            $booking = Booking::create([
                'user_id' => $request->user()->id,

                /*
                |--------------------------------------------------------------------------
                | Primary Relation
                |--------------------------------------------------------------------------
                */

                'motorcycle_stock_id' => $availableStock->id,

                /*
                |--------------------------------------------------------------------------
                | Legacy Compatibility
                |--------------------------------------------------------------------------
                |
                | motorcycle_id masih disimpan sementara karena beberapa controller,
                | dashboard, dan view masih menggunakannya. Nantinya field ini akan
                | dihapus setelah seluruh sistem menggunakan MotorcycleStock.
                |
                */

                'motorcycle_id' => $motorcycle->id,

                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'duration_days' => $durationDays,
                'delivery_location' => $validated['delivery_location'],
                'customer_note' => $validated['customer_note'] ?? null,
                'price_per_day' => $pricePerDay,
                'total_price' => $totalPrice,
                'status' => Booking::STATUS_PENDING_APPROVAL,
                'terms_accepted_at' => now(),
                'terms_version' => Booking::TERMS_VERSION,
                'terms_ip_address' => $request->ip(),
            ]);

            $availableStock->update([
                'status' => MotorcycleStock::STATUS_BOOKED,
            ]);

            $availableStock->refresh();

            $booking->recordStatusHistory(
                null,
                Booking::STATUS_PENDING_APPROVAL,
                $request->user()->id,
                'Booking dibuat oleh penyewa.'
            );

            $booking->refresh();

            return redirect()
                ->route('bookings.show', $booking)
                ->with('success', 'Pengajuan penyewaan berhasil dikirim.');
        });
    }

    public function show(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load([
            'motorcycle',
            'motorcycleStock.motorcycle',
            'latestPayment',
            'latestRentalPayment',
            'latestAdditionalChargePayment',
            'rentalChecklist',
            'rentalSafetyScore',
            'statusHistories.changedBy',
        ]);

        return view('bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        if (! $booking->canBeCancelledByCustomer()) {
            return back()->with('error', 'Booking tidak bisa dibatalkan.');
        }

        return DB::transaction(function () use ($request, $booking) {

            $oldStatus = $booking->status;

            $booking->update([
                'status' => Booking::STATUS_CANCELLED,
                'cancelled_at' => now(),
            ]);

            if ($booking->motorcycleStock) {

                $booking->motorcycleStock->update([
                    'status' => MotorcycleStock::STATUS_AVAILABLE,
                ]);

                $booking->syncMotorcycleStock();

            }

            $booking->recordStatusHistory(
                $oldStatus,
                Booking::STATUS_CANCELLED,
                $request->user()->id,
                'Booking dibatalkan oleh penyewa.'
            );

            $booking->refresh();

            return redirect()
                ->route('dashboard')
                ->with('success', 'Booking berhasil dibatalkan.');
        });
    }

}