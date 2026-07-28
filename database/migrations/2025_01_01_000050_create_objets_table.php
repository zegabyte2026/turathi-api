<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endroit_id')->constrained()->cascadeOnDelete();
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('images')->nullable();
            $table->json('audio_paths')->nullable();
            $table->string('qr_code_id')->unique()->nullable();
            $table->string('materiau')->nullable();
            $table->string('periode')->nullable();
            $table->string('dimensions')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('total_visits')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objets');
    }
};
