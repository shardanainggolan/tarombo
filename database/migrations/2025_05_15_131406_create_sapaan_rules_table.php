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
        Schema::create("sapaan_rules", function (Blueprint $table) {
            $table->id();
            $table->foreignId("marga_id")->nullable()->constrained("margas")->onDelete("cascade"); // Rule specific to a marga, or null for general
            $table->string("relationship_type"); // e.g., "father", "mother", "paternal_uncle", "maternal_aunt", "son_in_law", "daughter_in_law", "lae", "eda", "ito_male_to_female", "ito_female_to_male", "pariban_male_to_female", "pariban_female_to_male"
            $table->enum("gender_from", ["male", "female", "other"])->nullable(); // Gender of the person addressing
            $table->enum("gender_to", ["male", "female", "other"])->nullable(); // Gender of the person being addressed
            // Add other conditions like age difference, generation difference if needed in future
            // $table->string("condition_details_json")->nullable(); // For more complex, less common rules
            $table->foreignId("sapaan_term_id")->constrained("sapaan_terms")->onDelete("cascade"); // The sapaan term to use
            $table->integer("priority")->default(0); // For rule precedence if multiple rules match
            $table->text("description")->nullable(); // Explanation of the rule
            $table->timestamps();

            // Index for faster lookups
            // $table->index(["marga_id", "relationship_type", "gender_from", "gender_to"]);
            $table->index(
                ["marga_id", "relationship_type", "gender_from", "gender_to"],
                "sapaan_rules_main_index"
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("sapaan_rules");
    }
};
