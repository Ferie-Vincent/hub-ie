<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->date('event_date');
            $table->timestamp('scanned_at');
            $table->foreignId('scanned_by_user_id')->constrained('users');
            $table->string('location');
            $table->string('scan_method');
            $table->string('scanner_ip', 45)->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'event_date']);
            $table->index('event_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
