<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('editions', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique();
            $table->string('title');
            $table->string('theme')->nullable();
            $table->string('location')->default('Abidjan, Côte d\'Ivoire');
            $table->timestamp('application_opens_at')->nullable();
            $table->timestamp('application_closes_at')->nullable();
            $table->timestamp('event_starts_at')->nullable();
            $table->timestamp('event_ends_at')->nullable();
            $table->unsignedSmallInteger('max_participants')->default(180);
            $table->unsignedTinyInteger('quota_women_min_pct')->default(50);
            $table->unsignedTinyInteger('quota_youth_min_pct')->default(40);
            $table->unsignedTinyInteger('quota_youth_max_age')->default(35);
            $table->boolean('registration_open')->default(false);
            $table->boolean('is_active')->default(false);
            $table->timestamp('launched_at')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('editions');
    }
};
