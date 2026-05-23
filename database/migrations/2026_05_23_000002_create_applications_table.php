<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code', 20)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('draft');
            $table->unsignedTinyInteger('current_step')->default(1);

            // Étape 2 — Profil professionnel
            $table->string('category')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('organization_type')->nullable();
            $table->string('position')->nullable();
            $table->string('sector')->nullable();
            $table->unsignedTinyInteger('experience_years')->nullable();
            $table->string('rccm_number')->nullable();
            $table->string('website')->nullable();
            $table->string('professional_email')->nullable();
            $table->string('professional_phone', 20)->nullable();

            // Étape 3 — Motivation
            $table->text('motivation')->nullable();
            $table->json('chosen_workshops')->nullable();
            $table->text('expectations')->nullable();
            $table->string('referral_source')->nullable();
            $table->boolean('is_first_participation')->nullable();

            // Étape 4 — Consentements
            $table->boolean('rgpd_consent')->default(false);
            $table->boolean('communication_consent')->default(false);

            // Soumission
            $table->timestamp('submitted_at')->nullable();
            $table->string('submission_ip', 45)->nullable();
            $table->string('submission_user_agent')->nullable();

            // Évaluation
            $table->decimal('average_score', 5, 2)->nullable();
            $table->unsignedSmallInteger('evaluations_count')->default(0);

            // Acceptation
            $table->string('group_label', 2)->nullable();
            $table->string('qr_token', 60)->unique()->nullable();
            $table->string('check_in_code', 6)->unique()->nullable();
            $table->string('badge_path')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('notified_at')->nullable();

            // Admin
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'submitted_at']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
