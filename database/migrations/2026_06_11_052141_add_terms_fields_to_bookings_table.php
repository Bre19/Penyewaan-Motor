<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('terms_accepted_at')->nullable()->after('status');
            $table->string('terms_version', 30)->nullable()->after('terms_accepted_at');
            $table->string('terms_ip_address', 45)->nullable()->after('terms_version');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'terms_accepted_at',
                'terms_version',
                'terms_ip_address',
            ]);
        });
    }
};