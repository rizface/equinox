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
        Schema::create('solutions', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->foreignUuid("coder_id")->references("id")->on("coders");
            $table->foreignUuid("question_id")->references("id")->on("questions");
            $table->text("solution");
            $table->enum("status", ["success", "fail"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solutions');
    }
};
