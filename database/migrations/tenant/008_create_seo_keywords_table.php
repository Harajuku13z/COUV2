<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('keyword');
            $table->enum('type', ['primary', 'secondary', 'lsi', 'long_tail']);
            $table->unsignedInteger('estimated_volume')->nullable();
            $table->unsignedTinyInteger('current_position')->nullable();
            $table->timestamps();

            $table->index(['page_id', 'type']);
            $table->index('keyword');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_keywords');
    }
};
