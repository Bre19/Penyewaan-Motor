<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(array_keys(Payment::statusLabels()))],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Payment::with(['booking.motorcycle', 'user'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $validated['status']);
        }

        if ($request->filled('search')) {
            $search = $validated['search'];

            $query->where(function ($paymentQuery) use ($search) {
                $paymentQuery
                    ->where('payment_code', 'like', "%{$search}%")
                    ->orWhereHas('booking', function ($bookingQuery) use ($search) {
                        $bookingQuery->where('booking_code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('booking.motorcycle', function ($motorcycleQuery) use ($search) {
                        $motorcycleQuery
                            ->where('brand', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%")
                            ->orWhere('plate_number', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->paginate(10)->withQueryString();

        return view('admin.payments.index', [
            'payments' => $payments,
            'statusLabels' => Payment::statusLabels(),
        ]);
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.motorcycle', 'user']);

        return view('admin.payments.show', compact('payment'));
    }

    public function confirm(Payment $payment)
    {
        if ($payment->status !== Payment::STATUS_WAITING_VERIFICATION) {
            return back()->with('error', 'Pembayaran ini tidak berada pada status menunggu verifikasi.');
        }

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => Payment::STATUS_CONFIRMED,
                'verified_at' => now(),
                'rejected_at' => null,
                'rejection_reason' => null,
            ]);

            $payment->booking->update([
                'status' => Booking::STATUS_PAYMENT_CONFIRMED,
            ]);
        });

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== Payment::STATUS_WAITING_VERIFICATION) {
            return back()->with('error', 'Pembayaran ini tidak berada pada status menunggu verifikasi.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($payment, $validated) {
            $payment->update([
                'status' => Payment::STATUS_REJECTED,
                'rejected_at' => now(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            $payment->booking->update([
                'status' => Booking::STATUS_WAITING_PAYMENT,
            ]);
        });

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil ditolak. Booking dikembalikan ke status menunggu pembayaran.');
    }
}