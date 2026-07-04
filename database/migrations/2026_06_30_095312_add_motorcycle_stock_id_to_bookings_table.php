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
        Schema::table('bookings', function (Blueprint $table) {

            $table->foreignId('motorcycle_stock_id')
                ->nullable()
                ->after('motorcycle_id')
                ->constrained('motorcycle_stocks')
                ->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropForeign(['motorcycle_stock_id']);
            $table->dropColumn('motorcycle_stock_id');

        });
    }
};