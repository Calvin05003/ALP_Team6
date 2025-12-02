<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_analyses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cv_submission_id')->constrained('cv_submissions')->cascadeOnDelete();

            $table->unsignedInteger('resume_score')->default(0);

            // data AI berbentuk JSON
            $table->json('main_skills_json')->nullable();
            $table->json('division_recommendations_json')->nullable();
            $table->json('skill_gap_json')->nullable();
            $table->json('readiness_scores_json')->nullable();

            $table->longText('feedback_text')->nullable();

            // debugging AI response
            $table->longText('raw_ai_response')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_analyses');
    }
};