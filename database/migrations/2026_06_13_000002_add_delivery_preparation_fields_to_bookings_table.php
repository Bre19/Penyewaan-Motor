<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'ready_to_deliver_at')) {
                $table->timestamp('ready_to_deliver_at')
                    ->nullable()
                    ->after('cancelled_at');
            }

            if (! Schema::hasColumn('bookings', 'delivery_preparation_note')) {
                $table->text('delivery_preparation_note')
                    ->nullable()
                    ->after('ready_to_deliver_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = [];

            foreach ([
                'ready_to_deliver_at',
                'delivery_preparation_note',
            ] as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};