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
        Schema::create("sapaan_terms", function (Blueprint $table) {
            $table->id();
            $table->string("term")->unique(); // Nama sapaan, e.g., Amang, Inang, Tulang, Lae
            $table->text("description")->nullable(); // Penjelasan sapaan
            $table->foreignId("marga_id")->nullable()->constrained("margas")->onDelete("cascade"); // Jika null, berlaku untuk semua marga
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("sapaan_terms");
    }
};
