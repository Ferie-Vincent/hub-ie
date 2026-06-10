<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->unsignedSmallInteger('registered_count')->default(0)->after('capacity');
        });

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('workshop_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('enrolled'); // enrolled|waitlisted|cancelled
            $table->string('badge_status')->nullable();    // valid|invalid (null tant que waitlisted)
            $table->string('qr_token', 60)->unique()->nullable();
            $table->string('check_in_code', 6)->unique()->nullable();
            $table->string('badge_path')->nullable();
            $table->timestamp('waitlist_offered_at')->nullable(); // quand l'admin a proposé la place
            $table->string('cancellation_token', 80)->unique()->nullable();
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['workshop_id', 'status']);
            $table->index('status');
            $table->index('cancellation_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');

        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn('registered_count');
        });
    }
};
