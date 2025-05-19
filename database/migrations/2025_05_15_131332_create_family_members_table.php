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
        Schema::create("family_members", function (Blueprint $table) {
            $table->id();
            $table->foreignId("family_id")->constrained("families")->onDelete("cascade");
            $table->string("full_name");
            $table->string("nickname")->nullable();
            $table->enum("gender", ["male", "female", "other"]);
            $table->date("birth_date")->nullable();
            $table->string("birth_place")->nullable();
            $table->date("death_date")->nullable();
            $table->string("death_place")->nullable();
            $table->foreignId("father_id")->nullable()->constrained("family_members")->onDelete("set null");
            $table->foreignId("mother_id")->nullable()->constrained("family_members")->onDelete("set null");
            $table->integer("order_in_siblings")->nullable(); // Urutan anak ke berapa
            $table->text("bio")->nullable();
            $table->string("profile_picture_path")->nullable();
            $table->foreignId("user_account_id")->nullable()->constrained("users")->onDelete("set null"); // Link to user account if this member is a registered user
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("family_members");
    }
};
