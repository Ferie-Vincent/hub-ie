<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_inscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('entreprise');
            $table->string('secteur');
            $table->string('atelier'); // slug de l'atelier choisi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_inscriptions');
    }
};
