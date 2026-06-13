<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('editions', function (Blueprint $table) {
            $table->json('institutions')->nullable()->after('programme_j4');
            $table->json('formats_echange')->nullable()->after('institutions');
            $table->string('newsletter_title')->nullable()->after('formats_echange');
            $table->text('newsletter_subtitle')->nullable()->after('newsletter_title');
        });
    }

    public function down(): void
    {
        Schema::table('editions', function (Blueprint $table) {
            $table->dropColumn(['institutions', 'formats_echange', 'newsletter_title', 'newsletter_subtitle']);
        });
    }
};
