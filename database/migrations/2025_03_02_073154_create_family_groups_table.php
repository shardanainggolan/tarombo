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
        Schema::create('family_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('father_id')->nullable();
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('father_id')
                  ->references('id')
                  ->on('people')
                  ->onDelete('set null');
                  
            $table->foreign('mother_id')
                  ->references('id')
                  ->on('people')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_groups');
    }
};
