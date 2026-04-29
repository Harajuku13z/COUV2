<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('service', 100)->index();
            $table->string('method', 10)->nullable();
            $table->text('endpoint')->nullable();
            $table->unsignedSmallInteger('status_code')->nullable()->index();
            $table->text('error_message');
            $table->string('exception_class')->nullable();
            $table->json('request_payload')->nullable();
            $table->longText('response_payload')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->json('context')->nullable();
            $table->timestamp('occurred_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_error_logs');
    }
};
