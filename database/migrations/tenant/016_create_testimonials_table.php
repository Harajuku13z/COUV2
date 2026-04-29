<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('author_city')->nullable();
            $table->string('service_label')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('content');
            $table->boolean('is_visible')->default(true)->index();
            $table->enum('source', ['manual', 'google'])->default('manual')->index();
            $table->string('google_review_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
