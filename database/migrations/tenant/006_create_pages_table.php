<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->enum('page_type', ['home', 'service', 'city', 'service_city', 'urgence', 'devis', 'meteo', 'blog', 'faq'])->index();
            $table->string('slug')->unique();
            $table->enum('status', ['draft', 'published', 'indexing', 'indexed'])->default('draft')->index();
            $table->decimal('similarity_score', 5, 2)->nullable();
            $table->timestamp('last_generated_at')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('lead_count')->default(0);
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['city_id', 'service_id', 'page_type']);
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
