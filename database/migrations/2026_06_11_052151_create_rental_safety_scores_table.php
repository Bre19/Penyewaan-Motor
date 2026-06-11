<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rental_safety_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->boolean('no_violation_report')->default(true);
            $table->boolean('negligent_damage')->default(false);
            $table->boolean('reckless_report')->default(false);

            $table->unsignedTinyInteger('score')->default(0);
            $table->boolean('badge_awarded')->default(false);

            $table->text('notes')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_safety_scores');
    }
};