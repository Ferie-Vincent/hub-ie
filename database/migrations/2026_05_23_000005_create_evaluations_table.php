<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('score_profile');
            $table->unsignedTinyInteger('score_motivation');
            $table->unsignedTinyInteger('score_relevance');
            $table->unsignedTinyInteger('score_representativity');
            $table->unsignedTinyInteger('score_balance');
            $table->decimal('weighted_score', 5, 2);
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'evaluator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
