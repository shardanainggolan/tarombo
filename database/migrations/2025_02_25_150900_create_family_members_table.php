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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('marga');
            $table->foreignId('father_id')->nullable()->constrained('family_members')->onDelete('cascade');
            $table->foreignId('mother_id')->nullable()->constrained('family_members')->onDelete('cascade');
            $table->foreignId('spouse_id')->nullable()->constrained('family_members')->onDelete('cascade');
            $table->enum('gender', ['male', 'female']);
            $table->enum('position_in_family', ['son', 'daughter', 'husband', 'wife', 'brother', 'sister']);
            $table->integer('marriage_count')->default(0);
            $table->date('birth_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
