<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SapaanTerm;
use App\Models\Marga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SapaanTermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Allow filtering by marga_id if provided
        $query = SapaanTerm::with("marga");
        if ($request->has("marga_id")) {
            $query->where("marga_id", $request->input("marga_id"));
        }
        $sapaanTerms = $query->paginate(15);
        return view("admin.pages.sapaan_terms.index", compact("sapaanTerms"));
        // return response()->json($sapaanTerms); // Placeholder
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $margas = Marga::all(); // For optional marga selection
        return view("admin.pages.sapaan_terms.create", compact("margas"));
        // return response()->json(["message" => "Show form to create sapaan term", "margas" => $margas]); // Placeholder
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "term" => "required|string|max:255|unique:sapaan_terms,term", // Term should be unique globally for now
            "description" => "nullable|string",
            "marga_id" => "nullable|exists:margas,id",
        ]);

        $sapaanTerm = SapaanTerm::create($validatedData);
        return redirect()->route("admin.sapaan_terms.index")->with("success", "Sapaan term created successfully.");
        // return response()->json($sapaanTerm, 201); // Placeholder
    }

    /**
     * Display the specified resource.
     */
    public function show(SapaanTerm $sapaanTerm)
    {
        $sapaanTerm->load("marga");
        return view("admin.pages.sapaan_terms.show", compact("sapaanTerm"));
        // return response()->json($sapaanTerm); // Placeholder
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SapaanTerm $sapaanTerm)
    {
        $margas = Marga::all();
        return view("admin.pages.sapaan_terms.edit", compact("sapaanTerm", "margas"));
        // return response()->json(["sapaan_term" => $sapaanTerm, "margas" => $margas]); // Placeholder
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SapaanTerm $sapaanTerm)
    {
        $validatedData = $request->validate([
            "term" => ["required", "string", "max:255", Rule::unique("sapaan_terms", "term")->ignore($sapaanTerm->id)],
            "description" => "nullable|string",
            "marga_id" => "nullable|exists:margas,id",
        ]);

        $sapaanTerm->update($validatedData);
        return redirect()->route("admin.sapaan_terms.index")->with("success", "Sapaan term updated successfully.");
        // return response()->json($sapaanTerm); // Placeholder
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SapaanTerm $sapaanTerm)
    {
        // Consider implications: what happens to SapaanRules using this term?
        // Might need to set to null or prevent deletion if in use.
        // For now, simple delete.
        $sapaanTerm->delete();
        return redirect()->route("admin.sapaan_terms.index")->with("success", "Sapaan term deleted successfully.");
        // return response()->json(null, 204); // Placeholder
    }
}

