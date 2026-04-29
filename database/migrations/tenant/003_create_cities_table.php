<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('code_insee', 5)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('department_code', 3);
            $table->string('postal_code', 5)->nullable();
            $table->unsignedInteger('population')->default(0);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->decimal('surface', 8, 2)->nullable();
            $table->unsignedTinyInteger('seo_priority')->default(5)->index();
            $table->json('nearby_cities')->default('[]');
            $table->boolean('is_active')->default(true)->index();
            $table->json('weather_data')->nullable();
            $table->timestamp('weather_updated_at')->nullable();
            $table->timestamps();

            $table->foreign('department_code')->references('code')->on('departments')->cascadeOnUpdate()->restrictOnDelete();
            $table->index(['department_code', 'seo_priority']);
            $table->index(['slug', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
