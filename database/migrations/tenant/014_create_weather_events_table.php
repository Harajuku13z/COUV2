<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('event_type', ['rain', 'wind', 'frost', 'heatwave', 'storm', 'flood', 'snow'])->index();
            $table->enum('intensity', ['low', 'medium', 'high', 'extreme'])->index();
            $table->date('event_date')->index();
            $table->text('description')->nullable();
            $table->boolean('used_for_content')->default(false)->index();
            $table->timestamps();

            $table->index(['city_id', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_events');
    }
};
