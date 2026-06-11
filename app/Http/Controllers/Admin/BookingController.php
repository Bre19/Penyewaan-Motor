<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RentalChecklist;
use App\Models\RentalSafetyScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $booking->load([
            'user',
            'motorcycle',
            'latestPayment',
            'rentalChecklist',
            'rentalSafetyScore',
        ]);

        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Booking $booking)
    {
        if ($booking->status !== Booking::STATUS_PENDING_APPROVAL) {
            return back()->with('error', 'Booking ini tidak berada pada status menunggu persetujuan.');
        }

        $booking->update([
            'status' => Booking::STATUS_WAITING_PAYMENT,
            'approved_at' => now(),
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Booking berhasil disetujui dan menunggu pembayaran dari penyewa.');
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

    public function handover(Booking $booking)
    {
        if (! $booking->canBeHandedOverByAdmin()) {
            return redirect()
                ->route('admin.bookings.show', $booking)
                ->with('error', 'Checklist serah-terima hanya dapat dilakukan setelah pembayaran dikonfirmasi.');
        }

        $booking->load(['user', 'motorcycle']);

        return view('admin.bookings.handover', compact('booking'));
    }

    public function storeHandover(Request $request, Booking $booking)
    {
        if (! $booking->canBeHandedOverByAdmin()) {
            return redirect()
                ->route('admin.bookings.show', $booking)
                ->with('error', 'Checklist serah-terima hanya dapat dilakukan setelah pembayaran dikonfirmasi.');
        }

        $validated = $request->validate([
            'helmet_available' => ['accepted'],
            'brakes_normal' => ['accepted'],
            'headlight_normal' => ['accepted'],
            'brake_light_normal' => ['accepted'],
            'turn_signals_normal' => ['accepted'],
            'tires_proper' => ['accepted'],
            'mirrors_complete' => ['accepted'],
            'stnk_available' => ['accepted'],
            'motorcycle_condition_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'customer_with_helmet_photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], [
            '*.accepted' => 'Semua checklist wajib dipastikan normal sebelum motor diserahkan.',
        ]);

        DB::transaction(function () use ($request, $booking, $validated) {
            $motorcycleConditionPhoto = $request->file('motorcycle_condition_photo')
                ->store('rental-checklists', 'public');

            $customerWithHelmetPhoto = $request->file('customer_with_helmet_photo')
                ->store('rental-checklists', 'public');

            RentalChecklist::create([
                'booking_id' => $booking->id,
                'checked_by' => $request->user()->id,
                'helmet_available' => true,
                'brakes_normal' => true,
                'headlight_normal' => true,
                'brake_light_normal' => true,
                'turn_signals_normal' => true,
                'tires_proper' => true,
                'mirrors_complete' => true,
                'stnk_available' => true,
                'motorcycle_condition_photo' => $motorcycleConditionPhoto,
                'customer_with_helmet_photo' => $customerWithHelmetPhoto,
                'notes' => $validated['notes'] ?? null,
                'checked_at' => now(),
            ]);

            $booking->update([
                'status' => Booking::STATUS_ONGOING,
            ]);
        });

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Checklist serah-terima berhasil disimpan. Booking sekarang berstatus sedang berjalan.');
    }

    public function complete(Booking $booking)
    {
        if (! $booking->canBeCompletedByAdmin()) {
            return redirect()
                ->route('admin.bookings.show', $booking)
                ->with('error', 'Rental hanya dapat diselesaikan jika status booking sedang berjalan.');
        }

        $booking->load(['user', 'motorcycle', 'rentalChecklist']);

        return view('admin.bookings.complete', compact('booking'));
    }

    public function storeCompletion(Request $request, Booking $booking)
    {
        if (! $booking->canBeCompletedByAdmin()) {
            return redirect()
                ->route('admin.bookings.show', $booking)
                ->with('error', 'Rental hanya dapat diselesaikan jika status booking sedang berjalan.');
        }

        $validated = $request->validate([
            'no_violation_report' => ['nullable', 'boolean'],
            'negligent_damage' => ['nullable', 'boolean'],
            'reckless_report' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $negligentDamage = $request->boolean('negligent_damage');
        $recklessReport = $request->boolean('reckless_report');

        $noViolationReport = $request->boolean('no_violation_report');

        if ($negligentDamage || $recklessReport) {
            $noViolationReport = false;
        }

        $score = RentalSafetyScore::calculateScore(
            $noViolationReport,
            $negligentDamage,
            $recklessReport
        );

        $badgeAwarded = RentalSafetyScore::isTrustedRiderScore($score);

        DB::transaction(function () use (
            $request,
            $booking,
            $validated,
            $noViolationReport,
            $negligentDamage,
            $recklessReport,
            $score,
            $badgeAwarded
        ) {
            RentalSafetyScore::create([
                'booking_id' => $booking->id,
                'evaluated_by' => $request->user()->id,
                'no_violation_report' => $noViolationReport,
                'negligent_damage' => $negligentDamage,
                'reckless_report' => $recklessReport,
                'score' => $score,
                'badge_awarded' => $badgeAwarded,
                'notes' => $validated['notes'] ?? null,
                'evaluated_at' => now(),
            ]);

            $booking->update([
                'status' => Booking::STATUS_COMPLETED,
            ]);

            if ($badgeAwarded && ! $booking->user->trusted_rider_at) {
                $booking->user->forceFill([
                    'trusted_rider_at' => now(),
                ])->save();
            }
        });

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', 'Rental berhasil diselesaikan dan Safety Score telah dihitung.');
    }
}