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
        Schema::create('admin_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid("batch_token");
            $table->uuid("submission_token")->nullable(false);
            $table->foreignUuid("question_id")->references("id")->on("questions")->onDelete("cascade");
            $table->foreignUuid("admin_id")->references("id")->on("admins")->onDelete("cascade");
            $table->integer("lang_id");
            $table->text("source_code");
            $table->jsonb("params");
            $table->jsonb("expected_return_values");
            $table->string("status")->nullable(false)->default("pending");
            $table->jsonb("result")->nullable(true)->default(null);
            $table->boolean("is_correct")->nullable(true)->default(null);
            $table->timestamps();
            $table->unique(["batch_token", "submission_token"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_submissions');
    }
};
