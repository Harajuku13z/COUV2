<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('local_pack_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serp_result_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('position');
            $table->string('name');
            $table->string('address')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->unsignedInteger('review_count')->nullable();
            $table->text('url')->nullable();
            $table->text('maps_url')->nullable();
            $table->string('phone')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->string('category')->nullable();
            $table->timestamps();

            $table->index(['serp_result_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('local_pack_results');
    }
};
