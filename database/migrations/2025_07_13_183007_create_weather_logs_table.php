<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('temperature', 5, 2)->nullable();
            $table->integer('humidity')->nullable();
            $table->decimal('wind_speed', 5, 2)->nullable();
            $table->integer('pressure')->nullable();
            $table->decimal('precipitation', 5, 2)->nullable();
            $table->decimal('lat', 8, 5);
            $table->decimal('lon', 8, 5);
            $table->string('source', 50);
            $table->timestamp('timestamp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_logs');
    }
};
