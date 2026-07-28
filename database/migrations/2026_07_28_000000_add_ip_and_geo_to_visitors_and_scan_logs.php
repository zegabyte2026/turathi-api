<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('device_id');
            $table->string('city')->nullable()->after('ip_address');
            $table->string('region')->nullable()->after('city');
            $table->string('country')->nullable()->after('region');
            $table->decimal('latitude', 10, 7)->nullable()->after('country');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });

        Schema::table('scan_logs', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('device_info');
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'city', 'region', 'country', 'latitude', 'longitude']);
        });

        Schema::table('scan_logs', function (Blueprint $table) {
            $table->dropColumn('ip_address');
        });
    }
};
