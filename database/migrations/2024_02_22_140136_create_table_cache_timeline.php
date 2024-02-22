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
            $table->foreignUuid("coder_id")->references("id")->on("coders")->onDelete("cascade");
            $table->text("title");
            $table->text("type");
            $table->text("parent")->nullable();
            $table->timestamp("solved_at");
            $table->index("coder_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_cache_timeline');
    }
};
