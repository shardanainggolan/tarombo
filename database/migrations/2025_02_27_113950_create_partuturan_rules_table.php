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
            $table->foreignId('group_id')->constrained('partuturan_groups');
            $table->string('relationship_code', 50); // Contoh: mother.brother.son
            $table->string('term', 50); // Contoh: Lae
            $table->string('gender', 10)->nullable(); // male, female, both
            $table->integer('min_generation')->nullable();
            $table->integer('max_generation')->nullable();
            $table->boolean('marriage_required')->default(false);
            $table->text('condition')->nullable(); // SQL logic tambahan
            $table->integer('priority')->default(0);
            $table->timestamps();
            
            $table->unique(['group_id', 'relationship_code']);
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
