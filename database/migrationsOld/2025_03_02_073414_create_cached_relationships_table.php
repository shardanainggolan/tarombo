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
        Schema::create('cached_relationships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_person_id');
            $table->unsignedBigInteger('to_person_id');
            $table->unsignedBigInteger('relationship_pattern_id');
            $table->unsignedBigInteger('partuturan_term_id');
            $table->timestamps();
            
            // Handle multiple foreign keys to the same table
            $table->foreign('from_person_id')
                  ->references('id')
                  ->on('people')
                  ->onDelete('cascade');
                  
            $table->foreign('to_person_id')
                  ->references('id')
                  ->on('people')
                  ->onDelete('cascade');
                  
            $table->foreign('relationship_pattern_id')
                  ->references('id')
                  ->on('relationship_patterns')
                  ->onDelete('cascade');
                  
            $table->foreign('partuturan_term_id')
                  ->references('id')
                  ->on('partuturan_terms')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cached_relationships');
    }
};
