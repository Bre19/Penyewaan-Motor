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

            // Kode internal unit
            $table->string('stock_code')->unique();

            // Plat kendaraan
            $table->string('plate_number')->unique();

            // Foto unit ini
            $table->string('image')->nullable();

            // Status unit
            $table->enum('status', [
                'available',
                'booked',
                'rented',
                'maintenance',
                'inactive',
            ])->default('available');

            // GPS Dummy (persiapan fitur tracking)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamp('last_gps_update_at')->nullable();

            // Catatan admin
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index([
                'motorcycle_id',
                'status',
            ]);

            $table->index([
                'latitude',
                'longitude',
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