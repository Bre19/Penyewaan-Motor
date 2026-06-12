<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function create(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['motorcycle', 'latestRentalPayment', 'latestAdditionalChargePayment']);

        $context = $this->resolvePaymentContext($booking);

        if (! $context) {
            return redirect()
                ->route('bookings.show', $booking)
                ->with('error', 'Booking ini belum dapat melakukan pembayaran atau sedang menunggu verifikasi.');
        }

        return view('payments.create', [
            'booking' => $booking,
            'paymentType' => $context['type'],
            'paymentTitle' => $context['title'],
            'paymentDescription' => $context['description'],
            'paymentAmount' => $context['amount'],
            'paymentTypeLabel' => $context['label'],
        ]);
    }

    public function store(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['latestRentalPayment', 'latestAdditionalChargePayment']);

        $context = $this->resolvePaymentContext($booking);

        if (! $context) {
            return redirect()
                ->route('bookings.show', $booking)
                ->with('error', 'Booking ini belum dapat melakukan pembayaran.');
        }

        $latestPayment = $booking->payments()
            ->where('payment_type', $context['type'])
            ->latest()
            ->first();

        if (
            $latestPayment
            && in_array($latestPayment->status, [
                Payment::STATUS_WAITING_VERIFICATION,
                Payment::STATUS_CONFIRMED,
            ], true)
        ) {
            return redirect()
                ->route('bookings.show', $booking)
                ->with('error', 'Pembayaran untuk kebutuhan ini sudah dikirim atau sudah dikonfirmasi.');
        }

        $validated = $request->validate([
            'method' => ['required', Rule::in(array_keys(Payment::methodLabels()))],
            'payer_name' => ['required', 'string', 'max:255'],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $booking, $validated, $context) {
            $proofPath = $request->file('proof')->store('payment-proofs', 'public');

            Payment::create([
                'booking_id' => $booking->id,
                'user_id' => $request->user()->id,
                'payment_type' => $context['type'],
                'amount' => $context['amount'],
                'method' => $validated['method'],
                'payer_name' => $validated['payer_name'],
                'proof_path' => $proofPath,
                'note' => $validated['note'] ?? null,
                'status' => Payment::STATUS_WAITING_VERIFICATION,
                'uploaded_at' => now(),
            ]);

            if ($context['type'] === Payment::TYPE_RENTAL_FEE) {
                $booking->update([
                    'status' => Booking::STATUS_WAITING_PAYMENT_VERIFICATION,
                ]);
            }

            if ($context['type'] === Payment::TYPE_ADDITIONAL_CHARGE) {
                $booking->update([
                    'additional_charge_status' => Booking::ADDITIONAL_CHARGE_WAITING_VERIFICATION,
                ]);
            }
        });

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Bukti pembayaran berhasil dikirim dan menunggu verifikasi admin.');
    }

    private function resolvePaymentContext(Booking $booking): ?array
    {
        if ($booking->canUploadRentalPaymentProof()) {
            return [
                'type' => Payment::TYPE_RENTAL_FEE,
                'label' => 'Pembayaran Sewa',
                'title' => 'Upload bukti pembayaran sewa.',
                'description' => 'Kirim bukti pembayaran utama agar admin dapat melakukan verifikasi.',
                'amount' => (float) $booking->total_price,
            ];
        }

        if ($booking->canUploadAdditionalChargePaymentProof()) {
            return [
                'type' => Payment::TYPE_ADDITIONAL_CHARGE,
                'label' => 'Biaya Tambahan',
                'title' => 'Upload bukti pembayaran biaya tambahan.',
                'description' => 'Kirim bukti pembayaran tambahan atas kerusakan, keterlambatan, atau biaya lain setelah pengembalian motor.',
                'amount' => (float) $booking->additional_charge_amount,
            ];
        }

        return null;
    }
}