<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->integer('total_visits')->default(0)->after('is_published');
            $table->integer('unique_visitors')->default(0)->after('total_visits');
        });

        Schema::table('endroits', function (Blueprint $table) {
            $table->integer('total_visits')->default(0)->after('is_published');
            $table->integer('unique_visitors')->default(0)->after('total_visits');
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn(['total_visits', 'unique_visitors']);
        });

        Schema::table('endroits', function (Blueprint $table) {
            $table->dropColumn(['total_visits', 'unique_visitors']);
        });
    }
};
