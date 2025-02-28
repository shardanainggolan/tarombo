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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->enum('gender', ['male', 'female']);
            $table->string('marga');
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->boolean('is_boru_line')->default(false);
            $table->text('bio')->nullable();
            $table->json('position')->nullable()->comment('
                {
                    "birth_order": 3, 
                    "gender_order": 1,
                    "marriage_order": 2
                }
            ');
            $table->foreignId('father_id')->nullable()->constrained('people');
            $table->foreignId('mother_id')->nullable()->constrained('people');
            $table->timestamps();

            $table->index(['marga', 'father_id', 'mother_id']);
            $table->index(['gender', 'is_boru_line']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
