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
            $table->boolean("for_all_coder")->nullable(false)->default(false);
            $table->boolean("for_all_course_admin")->nullable(false)->default(false);
            $table->uuid("for_admin_id")->nullable(true);
            $table->uuid("for_coder_id")->nullable(true);
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
