<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'additional_charge_amount')) {
                $table->decimal('additional_charge_amount', 12, 2)
                    ->default(0)
                    ->after('delivery_preparation_note');
            }

            if (! Schema::hasColumn('bookings', 'additional_charge_reason')) {
                $table->text('additional_charge_reason')
                    ->nullable()
                    ->after('additional_charge_amount');
            }

            if (! Schema::hasColumn('bookings', 'additional_charge_status')) {
                $table->string('additional_charge_status', 50)
                    ->default('none')
                    ->after('additional_charge_reason')
                    ->index();
            }

            if (! Schema::hasColumn('bookings', 'additional_charge_requested_at')) {
                $table->timestamp('additional_charge_requested_at')
                    ->nullable()
                    ->after('additional_charge_status');
            }

            if (! Schema::hasColumn('bookings', 'additional_charge_confirmed_at')) {
                $table->timestamp('additional_charge_confirmed_at')
                    ->nullable()
                    ->after('additional_charge_requested_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $columns = [];

            foreach ([
                'additional_charge_amount',
                'additional_charge_reason',
                'additional_charge_status',
                'additional_charge_requested_at',
                'additional_charge_confirmed_at',
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