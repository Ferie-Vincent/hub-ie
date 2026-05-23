<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo_path')->nullable();
            $table->string('logo_white_path')->nullable();
            $table->string('website')->nullable();
            $table->string('tier');
            $table->unsignedTinyInteger('display_order')->default(0);
            $table->boolean('show_in_marquee')->default(true);
            $table->boolean('show_in_footer')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
