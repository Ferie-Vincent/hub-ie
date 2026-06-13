<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('editions', function (Blueprint $table) {
            $table->text('description')->nullable()->after('theme');
            $table->string('cover_image')->nullable()->after('description');
            $table->json('key_figures')->nullable()->after('cover_image');
        });
    }

    public function down(): void
    {
        Schema::table('editions', function (Blueprint $table) {
            $table->dropColumn(['description', 'cover_image', 'key_figures']);
        });
    }
};
