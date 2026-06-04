<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone_number')) {
                $table->string('phone_number', 30)->nullable();
            }

            if (! Schema::hasColumn('users', 'current_address')) {
                $table->text('current_address')->nullable();
            }

            if (! Schema::hasColumn('users', 'passport_number')) {
                $table->string('passport_number', 100)->nullable();
            }

            if (! Schema::hasColumn('users', 'origin_country')) {
                $table->string('origin_country', 100)->nullable();
            }

            if (! Schema::hasColumn('users', 'has_license')) {
                $table->boolean('has_license')->default(false);
            }

            if (! Schema::hasColumn('users', 'role')) {
                $table->string('role', 30)->default('customer');
            }

            if (! Schema::hasColumn('users', 'status')) {
                $table->string('status', 30)->default('active');
            }
        });
    }

    public function down(): void
    {
        $columns = [];

        foreach ([
            'phone_number',
            'current_address',
            'passport_number',
            'origin_country',
            'has_license',
            'role',
            'status',
        ] as $column) {
            if (Schema::hasColumn('users', $column)) {
                $columns[] = $column;
            }
        }

        if ($columns !== []) {
            Schema::table('users', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};