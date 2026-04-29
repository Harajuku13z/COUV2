<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('city_label')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('service_requested')->nullable();
            $table->enum('urgency_level', ['low', 'medium', 'high', 'emergency'])->default('medium')->index();
            $table->text('message')->nullable();
            $table->json('uploaded_files')->default('[]');
            $table->text('source_url')->nullable();
            $table->string('keyword_targeted')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('ip_hash')->nullable();
            $table->enum('status', ['new', 'contacted', 'quoted', 'won', 'lost'])->default('new')->index();
            $table->text('notes')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index(['city_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
