<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_submissions', function (Blueprint $table) {
            $table->id();

            // optional jika user belum login
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('original_filename');
            $table->string('stored_path')->nullable();

            $table->longText('extracted_text')->nullable();

            $table->enum('input_mode', ['file', 'manual'])->default('file');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_submissions');
    }
};