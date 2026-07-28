<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pack_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->string('version');
            $table->string('hash');
            $table->enum('status', ['pending', 'compiling', 'ready', 'failed'])->default('pending');
            $table->integer('endroits_count')->default(0);
            $table->integer('images_count')->default(0);
            $table->integer('audios_count')->default(0);
            $table->integer('size_bytes')->default(0);
            $table->timestamp('compiled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pack_versions');
    }
};
