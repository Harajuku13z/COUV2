<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_generations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('model', 50);
            $table->text('prompt_system');
            $table->longText('prompt_user');
            $table->longText('raw_response')->nullable();
            $table->string('template_used', 50)->nullable();
            $table->unsignedInteger('prompt_tokens')->nullable();
            $table->unsignedInteger('completion_tokens')->nullable();
            $table->decimal('cost_usd', 10, 6)->nullable();
            $table->decimal('similarity_score', 5, 2)->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'too_similar'])->default('pending')->index();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['page_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_generations');
    }
};
