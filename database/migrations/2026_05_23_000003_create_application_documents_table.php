<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('original_name');
            $table->string('storage_path');
            $table->string('mime_type', 100);
            $table->unsignedInteger('size_bytes');
            $table->timestamps();

            $table->index('application_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_documents');
    }
};
