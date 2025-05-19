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
        Schema::create('parent_child', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('child_id');
            $table->unsignedBigInteger('marriage_id')->nullable();
            $table->boolean('is_biological')->default(true);
            $table->unsignedInteger('birth_order')->default(1);
            $table->timestamps();
            
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('people')
                  ->onDelete('cascade');
                  
            $table->foreign('child_id')
                  ->references('id')
                  ->on('people')
                  ->onDelete('cascade');
                  
            $table->foreign('marriage_id')
                  ->references('id')
                  ->on('marriages')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_child');
    }
};
