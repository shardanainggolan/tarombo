<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SapaanRule;
use App\Models\SapaanTerm;
use App\Models\Marga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SapaanRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SapaanRule::with(["marga", "sapaanTerm"]);

        if ($request->has("marga_id")) {
            $query->where("marga_id", $request->input("marga_id"));
        }
        if ($request->has("sapaan_term_id")) {
            $query->where("sapaan_term_id", $request->input("sapaan_term_id"));
        }
        if ($request->has("relationship_type")) {
            $query->where("relationship_type", "like", "%" . $request->input("relationship_type") . "%");
        }

        $sapaanRules = $query->orderBy("marga_id")->orderBy("priority", "desc")->paginate(20);
        return view("admin.pages.sapaan_rules.index", compact("sapaanRules"));
        // return response()->json($sapaanRules); // Placeholder
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $margas = Marga::all();
        $sapaanTerms = SapaanTerm::all();
        // Define relationship types, could be an enum or a config file later
        $relationshipTypes = [
            "father", "mother", "son", "daughter", "older_brother", "younger_brother", 
            "older_sister", "younger_sister", "husband", "wife",
            "paternal_grandfather", "paternal_grandmother", "maternal_grandfather", "maternal_grandmother",
            "paternal_uncle_older", "paternal_uncle_younger", "paternal_aunt_older", "paternal_aunt_younger",
            "maternal_uncle", "maternal_aunt",
            "cousin_from_paternal_uncle", "cousin_from_paternal_aunt", 
            "cousin_from_maternal_uncle", "cousin_from_maternal_aunt",
            "nephew", "niece", "grandson", "granddaughter",
            "father_in_law", "mother_in_law", "son_in_law", "daughter_in_law",
            "brother_in_law", "sister_in_law",
            "lae", "eda", "ito_male_to_female", "ito_female_to_male", 
            "pariban_male_to_female", "pariban_female_to_male",
            // Batak specific from previous research
            "amangboru", "namboru", "tulang", "nantulang",
            "amang_uda", "inang_uda", "amang_tua", "inang_tua",
            "ompung_doli", "ompung_boru"
        ];
        sort($relationshipTypes);

        return view("admin.pages.sapaan_rules.create", compact("margas", "sapaanTerms", "relationshipTypes"));
        // return response()->json(["margas" => $margas, "sapaan_terms" => $sapaanTerms, "relationship_types" => $relationshipTypes]); // Placeholder
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "marga_id" => "nullable|exists:margas,id",
            "relationship_type" => "required|string|max:255",
            "gender_from" => ["nullable", Rule::in(["male", "female", "other"])],
            "gender_to" => ["nullable", Rule::in(["male", "female", "other"])],
            "sapaan_term_id" => "required|exists:sapaan_terms,id",
            "priority" => "integer|default:0",
            "description" => "nullable|string",
        ]);

        $sapaanRule = SapaanRule::create($validatedData);
        return redirect()->route("admin.sapaan_rules.index")->with("success", "Sapaan rule created successfully.");
        // return response()->json($sapaanRule, 201); // Placeholder
    }

    /**
     * Display the specified resource.
     */
    public function show(SapaanRule $sapaanRule)
    {
        $sapaanRule->load(["marga", "sapaanTerm"]);
        return view("admin.pages.sapaan_rules.show", compact("sapaanRule"));
        // return response()->json($sapaanRule); // Placeholder
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SapaanRule $sapaanRule)
    {
        $margas = Marga::all();
        $sapaanTerms = SapaanTerm::all();
        $relationshipTypes = [
            "father", "mother", "son", "daughter", "older_brother", "younger_brother", 
            "older_sister", "younger_sister", "husband", "wife",
            "paternal_grandfather", "paternal_grandmother", "maternal_grandfather", "maternal_grandmother",
            "paternal_uncle_older", "paternal_uncle_younger", "paternal_aunt_older", "paternal_aunt_younger",
            "maternal_uncle", "maternal_aunt",
            "cousin_from_paternal_uncle", "cousin_from_paternal_aunt", 
            "cousin_from_maternal_uncle", "cousin_from_maternal_aunt",
            "nephew", "niece", "grandson", "granddaughter",
            "father_in_law", "mother_in_law", "son_in_law", "daughter_in_law",
            "brother_in_law", "sister_in_law",
            "lae", "eda", "ito_male_to_female", "ito_female_to_male", 
            "pariban_male_to_female", "pariban_female_to_male",
            "amangboru", "namboru", "tulang", "nantulang",
            "amang_uda", "inang_uda", "amang_tua", "inang_tua",
            "ompung_doli", "ompung_boru"
        ];
        sort($relationshipTypes);
        return view("admin.pages.sapaan_rules.edit", compact("sapaanRule", "margas", "sapaanTerms", "relationshipTypes"));
        // return response()->json(["sapaan_rule" => $sapaanRule, "margas" => $margas, "sapaan_terms" => $sapaanTerms, "relationship_types" => $relationshipTypes]); // Placeholder
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SapaanRule $sapaanRule)
    {
        $validatedData = $request->validate([
            "marga_id" => "nullable|exists:margas,id",
            "relationship_type" => "required|string|max:255",
            "gender_from" => ["nullable", Rule::in(["male", "female", "other"])],
            "gender_to" => ["nullable", Rule::in(["male", "female", "other"])],
            "sapaan_term_id" => "required|exists:sapaan_terms,id",
            "priority" => "integer|default:0",
            "description" => "nullable|string",
        ]);

        $sapaanRule->update($validatedData);
        return redirect()->route("admin.sapaan_rules.index")->with("success", "Sapaan rule updated successfully.");
        // return response()->json($sapaanRule); // Placeholder
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SapaanRule $sapaanRule)
    {
        $sapaanRule->delete();
        return redirect()->route("admin.sapaan_rules.index")->with("success", "Sapaan rule deleted successfully.");
        // return response()->json(null, 204); // Placeholder
    }
}

