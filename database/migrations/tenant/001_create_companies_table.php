<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->string('activity_main');
            $table->enum('activity_type', ['couvreur', 'plombier', 'peintre', 'electricien', 'elagueur', 'facadier', 'custom']);
            $table->string('siret')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('city');
            $table->string('postal_code', 10);
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->json('certifications')->default('[]');
            $table->text('offer_text')->nullable();
            $table->enum('tone', ['professionnel', 'chaleureux', 'urgent'])->default('professionnel');
            $table->string('gbp_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->json('opening_hours')->nullable();
            $table->boolean('emergency_available')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
