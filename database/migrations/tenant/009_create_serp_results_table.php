<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serp_results', function (Blueprint $table) {
            $table->id();
            $table->string('query')->index();
            $table->string('city_name')->nullable()->index();
            $table->string('service_name')->nullable()->index();
            $table->enum('engine', ['google', 'google_local', 'google_autocomplete', 'google_paa', 'google_maps'])->index();
            $table->longText('raw_response');
            $table->timestamp('analyzed_at')->index();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();

            $table->index(['query', 'engine', 'analyzed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('serp_results');
    }
};
