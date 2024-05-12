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
        Schema::create('cache_timeline', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("coder_id")->nullable(false);
            $table->uuid("question_id")->nullable(true);
            $table->uuid("contest_id")->nullable(true);
            $table->string("title");
            $table->string("type");
            $table->string("parent")->nullable(true);
            $table->dateTime("solved_at")->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache_timeline');
    }
};
