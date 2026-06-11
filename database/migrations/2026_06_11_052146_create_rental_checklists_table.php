<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();

            $table->boolean('helmet_available')->default(false);
            $table->boolean('brakes_normal')->default(false);
            $table->boolean('headlight_normal')->default(false);
            $table->boolean('brake_light_normal')->default(false);
            $table->boolean('turn_signals_normal')->default(false);
            $table->boolean('tires_proper')->default(false);
            $table->boolean('mirrors_complete')->default(false);
            $table->boolean('stnk_available')->default(false);

            $table->string('motorcycle_condition_photo');
            $table->string('customer_with_helmet_photo');

            $table->text('notes')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_checklists');
    }
};