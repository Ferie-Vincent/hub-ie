<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('short_description');
            $table->text('full_description')->nullable();
            $table->json('objectives')->nullable();
            $table->json('themes')->nullable();
            $table->string('icon_path')->nullable();
            $table->unsignedSmallInteger('capacity')->default(60);
            $table->unsignedTinyInteger('display_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
