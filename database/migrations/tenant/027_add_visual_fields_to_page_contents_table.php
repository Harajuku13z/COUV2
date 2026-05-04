<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            $table->string('featured_image_path')->nullable()->after('photo_suggestions');
            $table->string('featured_image_alt')->nullable()->after('featured_image_path');
            $table->json('realization_photos')->nullable()->after('featured_image_alt');
        });
    }

    public function down(): void
    {
        Schema::table('page_contents', function (Blueprint $table) {
            $table->dropColumn(['featured_image_path', 'featured_image_alt', 'realization_photos']);
        });
    }
};
