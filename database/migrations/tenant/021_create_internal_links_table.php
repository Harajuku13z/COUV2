<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_page_id')->constrained('pages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('to_page_id')->constrained('pages')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('anchor_text');
            $table->enum('link_type', ['service_in_city', 'service_in_nearby_city', 'blog', 'contact', 'devis', 'urgence'])->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->unique(['from_page_id', 'to_page_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_links');
    }
};
