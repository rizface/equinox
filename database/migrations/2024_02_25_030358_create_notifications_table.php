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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("from_id")->nullable(false);
            $table->uuid("to_id")->nullable(false);
            $table->foreignUuid("question_id")->references("id")->on("questions")->onDelete("cascade")->nullable(true);
            $table->boolean("seen")->nullable(true)->default(false); // only set true/false when receiver in specific coder or admin
            $table->text("title")->nullable(false);
            $table->text("message")->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
