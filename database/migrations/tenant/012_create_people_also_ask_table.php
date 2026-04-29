<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('people_also_ask', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serp_result_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('question');
            $table->text('answer_snippet')->nullable();
            $table->text('source_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people_also_ask');
    }
};
