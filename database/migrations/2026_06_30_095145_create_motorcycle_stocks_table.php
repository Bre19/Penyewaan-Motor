<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('motorcycle_stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('motorcycle_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('stock_code')->unique();

            $table->string('plate_number')->unique();

            $table->string('image')->nullable();

            $table->enum('status', [
                'available',
                'booked',
                'rented',
                'maintenance',
                'inactive',
            ])->default('available');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index([
                'motorcycle_id',
                'status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motorcycle_stocks');
    }
};