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
        Schema::create("spouses", function (Blueprint $table) {
            $table->id();
            $table->foreignId("family_member_id1")->constrained("family_members")->onDelete("cascade");
            $table->foreignId("family_member_id2")->constrained("family_members")->onDelete("cascade");
            $table->date("marriage_date")->nullable();
            $table->string("marriage_location")->nullable();
            $table->enum("status", ["married", "divorced", "widowed"])->default("married");
            $table->timestamps();

            $table->unique(["family_member_id1", "family_member_id2"]); // Ensure a pair is unique
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("spouses");
    }
};
