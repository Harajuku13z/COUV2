<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_services', function (Blueprint $table) {
            $table->text('keyword_focus')->nullable()->after('custom_description');
            $table->text('photo_brief')->nullable()->after('keyword_focus');
        });
    }

    public function down(): void
    {
        Schema::table('website_services', function (Blueprint $table) {
            $table->dropColumn(['keyword_focus', 'photo_brief']);
        });
    }
};
