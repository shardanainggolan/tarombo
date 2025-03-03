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
        Schema::create('marriages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('husband_id');
            $table->unsignedBigInteger('wife_id');
            $table->date('marriage_date')->nullable();
            $table->date('divorce_date')->nullable();
            $table->boolean('is_current')->default(true);
            $table->unsignedInteger('marriage_order')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Custom constraint names for multiple FKs to same table
            $table->foreign('husband_id')
                  ->references('id')
                  ->on('people')
                  ->onDelete('cascade');
                  
            $table->foreign('wife_id')
                  ->references('id')
                  ->on('people')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marriages');
    }
};
