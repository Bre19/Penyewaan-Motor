<?php

use App\Models\Booking;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('motorcycle_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('booking_code')->unique();

            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedSmallInteger('duration_days');

            $table->string('delivery_location');
            $table->text('customer_note')->nullable();

            $table->decimal('price_per_day', 12, 2);
            $table->decimal('total_price', 12, 2);

            $table->enum('status', [
                Booking::STATUS_PENDING_APPROVAL,
                Booking::STATUS_APPROVED,
                Booking::STATUS_REJECTED,
                Booking::STATUS_WAITING_PAYMENT,
                Booking::STATUS_WAITING_PAYMENT_VERIFICATION,
                Booking::STATUS_PAYMENT_CONFIRMED,
                Booking::STATUS_READY_TO_DELIVER,
                Booking::STATUS_ONGOING,
                Booking::STATUS_COMPLETED,
                Booking::STATUS_CANCELLED,
            ])->default(Booking::STATUS_PENDING_APPROVAL)->index();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            $table->index(['motorcycle_id', 'start_date', 'end_date']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};