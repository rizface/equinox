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
        Schema::create('coders', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("username")->nullable(false);
            $table->string("password")->nullable(false);
            $table->string("name")->nullable(false);
            $table->string("nim")->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coders');
    }
};
