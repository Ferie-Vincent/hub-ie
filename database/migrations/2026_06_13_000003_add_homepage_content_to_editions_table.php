<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('editions', function (Blueprint $table) {
            $table->text('hero_patron_text')->nullable()->after('key_figures');
            $table->text('hero_subtitle')->nullable()->after('hero_patron_text');
            $table->json('dates_cles')->nullable()->after('hero_subtitle');
            $table->string('minister_name')->nullable()->after('dates_cles');
            $table->string('minister_title')->nullable()->after('minister_name');
            $table->json('minister_speech')->nullable()->after('minister_title');
            $table->json('stats_cards')->nullable()->after('minister_speech');
            $table->json('cap_objectifs')->nullable()->after('stats_cards');
            $table->json('cap_resultats')->nullable()->after('cap_objectifs');
            $table->json('cap_pourquoi')->nullable()->after('cap_resultats');
            $table->json('programme_j1')->nullable()->after('cap_pourquoi');
            $table->json('programme_j2')->nullable()->after('programme_j1');
            $table->json('programme_j3')->nullable()->after('programme_j2');
            $table->json('programme_j4')->nullable()->after('programme_j3');
        });
    }

    public function down(): void
    {
        Schema::table('editions', function (Blueprint $table) {
            $table->dropColumn([
                'hero_patron_text', 'hero_subtitle', 'dates_cles',
                'minister_name', 'minister_title', 'minister_speech',
                'stats_cards', 'cap_objectifs', 'cap_resultats', 'cap_pourquoi',
                'programme_j1', 'programme_j2', 'programme_j3', 'programme_j4',
            ]);
        });
    }
};
