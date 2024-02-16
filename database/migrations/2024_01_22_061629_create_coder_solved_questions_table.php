<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coder_solved_questions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("coder_id")->references("id")->on("coders")->onDelete("cascade");
            $table->foreignUuid("question_id")->references("id")->on("questions")->onDelete("cascade");
            $table->unique(["coder_id", "question_id"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coder_solved_questions');
    }
};
