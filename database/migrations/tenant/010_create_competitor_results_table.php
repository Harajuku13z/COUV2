<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competitor_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serp_result_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedTinyInteger('rank');
            $table->string('name');
            $table->text('url');
            $table->string('title');
            $table->text('meta_description')->nullable();
            $table->decimal('google_rating', 2, 1)->nullable();
            $table->unsignedInteger('review_count')->nullable();
            $table->string('phone')->nullable();
            $table->text('gbp_url')->nullable();
            $table->timestamps();

            $table->index(['serp_result_id', 'rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitor_results');
    }
};
