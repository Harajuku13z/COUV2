<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->unique()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('meta_title', 70);
            $table->string('meta_description', 160);
            $table->string('h1', 200);
            $table->text('intro');
            $table->json('sections');
            $table->json('faq');
            $table->string('cta_primary', 100);
            $table->string('cta_secondary', 100);
            $table->text('short_excerpt')->nullable();
            $table->json('internal_links')->default('[]');
            $table->text('google_business_post')->nullable();
            $table->text('facebook_post')->nullable();
            $table->json('schema_local_business')->nullable();
            $table->json('schema_service')->nullable();
            $table->json('schema_faq')->nullable();
            $table->unsignedInteger('word_count')->default(0);
            $table->decimal('readability_score', 4, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_contents');
    }
};
