<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Drop FK first (MySQL requires FK dropped before unique index on same column)
            $table->dropForeign(['application_id']);
            $table->dropUnique(['application_id', 'event_date']);
            $table->dropColumn('application_id');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('enrollment_id')->after('id')->constrained()->cascadeOnDelete();
            $table->unique(['enrollment_id', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropUnique(['enrollment_id', 'event_date']);
            $table->dropColumn('enrollment_id');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('application_id')->after('id')->constrained()->cascadeOnDelete();
            $table->unique(['application_id', 'event_date']);
        });
    }
};
