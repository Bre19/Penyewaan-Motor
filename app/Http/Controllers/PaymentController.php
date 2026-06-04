<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function create(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['motorcycle', 'latestPayment']);

        if (! $booking->canUploadPaymentProof()) {
            return redirect()
                ->route('bookings.show', $booking)
                ->with('error', 'Booking ini belum dapat melakukan pembayaran atau sedang menunggu verifikasi.');
        }

        return view('payments.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load('latestPayment');

        if (! $booking->canUploadPaymentProof()) {
            return redirect()
                ->route('bookings.show', $booking)
                ->with('error', 'Booking ini belum dapat melakukan pembayaran.');
        }

        if (
            $booking->latestPayment
            && in_array($booking->latestPayment->status, [
                Payment::STATUS_WAITING_VERIFICATION,
                Payment::STATUS_CONFIRMED,
            ], true)
        ) {
            return redirect()
                ->route('bookings.show', $booking)
                ->with('error', 'Pembayaran untuk booking ini sudah dikirim atau sudah dikonfirmasi.');
        }

        $validated = $request->validate([
            'method' => ['required', Rule::in(array_keys(Payment::methodLabels()))],
            'payer_name' => ['required', 'string', 'max:255'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $booking, $validated) {
            $proofPath = $request->file('proof')->store('payment-proofs', 'public');

            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $request->user()->id,
                'amount' => $booking->total_price,
                'method' => $validated['method'],
                'payer_name' => $validated['payer_name'],
                'proof_path' => $proofPath,
                'note' => $validated['note'] ?? null,
                'status' => Payment::STATUS_WAITING_VERIFICATION,
                'uploaded_at' => now(),
            ]);

            $booking->update([
                'status' => Booking::STATUS_WAITING_PAYMENT_VERIFICATION,
            ]);
        });

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Bukti pembayaran berhasil dikirim dan menunggu verifikasi admin.');
    }
}