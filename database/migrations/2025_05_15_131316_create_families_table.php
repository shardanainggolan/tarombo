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
        Schema::create("families", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade"); // User who manages this family tree
            $table->foreignId("marga_id")->nullable()->constrained("margas")->onDelete("set null"); // Marga of this family
            $table->string("family_name"); // e.g., "Keluarga Besar Op. Raja Nainggolan"
            $table->text("description")->nullable();
            $table->boolean("is_public")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("families");
    }
};
