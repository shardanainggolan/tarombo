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
        Schema::create("margas", function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique(); // e.g., Nainggolan, Simatupang
            $table->text("description")->nullable();
            $table->string("origin_story_link")->nullable(); // Link to historical info
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("margas");
    }
};
