<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('related_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serp_result_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('query');
            $table->timestamps();

            $table->index(['serp_result_id', 'query']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('related_searches');
    }
};
