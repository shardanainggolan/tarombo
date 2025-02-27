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
        Schema::create('partuturan_rules', function (Blueprint $table) {
            $table->id();
            $table->string('relationship_type'); // Contoh: 'father_brother', 'mother_sister'
            $table->string('term'); // Contoh: 'tulang', 'namboru'
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partuturan_rules');
    }
};
