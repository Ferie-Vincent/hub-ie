<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_inscriptions', function (Blueprint $table) {
            $table->string('poste')->nullable()->after('secteur');
            $table->text('motivation_projet')->nullable()->after('poste');
            $table->text('motivation_objectifs')->nullable()->after('motivation_projet');
        });
    }

    public function down(): void
    {
        Schema::table('pre_inscriptions', function (Blueprint $table) {
            $table->dropColumn(['poste', 'motivation_projet', 'motivation_objectifs']);
        });
    }
};
