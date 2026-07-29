<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wilayas', function (Blueprint $table) {
            $table->json('name')->nullable()->change();
            $table->json('description')->nullable()->after('name');
        });

        if (DB::table('wilayas')->exists()) {
            DB::table('wilayas')->where('id', 1)->update(['name' => json_encode(['fr' => 'Tlemcen', 'ar' => 'تلمسان', 'en' => 'Tlemcen'])]);
            DB::table('wilayas')->where('id', 2)->update(['name' => json_encode(['fr' => 'Alger', 'ar' => 'الجزائر', 'en' => 'Algiers'])]);
            DB::table('wilayas')->where('id', 3)->update(['name' => json_encode(['fr' => 'Tipasa', 'ar' => 'تيبازة', 'en' => 'Tipaza'])]);
        }
    }

    public function down(): void
    {
        Schema::table('wilayas', function (Blueprint $table) {
            $table->string('name')->change();
            $table->dropColumn('description');
        });
    }
};
