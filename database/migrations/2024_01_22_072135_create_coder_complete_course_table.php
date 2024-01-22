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
        Schema::create('coder_complete_courses', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("coder_id")->references("id")->on("coders");
            $table->foreignUuid("course_id")->references("id")->on("contests");
            $table->unique(["coder_id", "course_id"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coder_complete_course');
    }
};
