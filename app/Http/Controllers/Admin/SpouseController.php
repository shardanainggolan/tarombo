<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Spouse;
use App\Models\FamilyMember;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SpouseController extends Controller
{
    // Note: Spouses are managed in the context of FamilyMembers within a Family.
    // Routes would typically be nested, e.g., /families/{family}/family_members/{member}/spouses

    /**
     * Display a listing of the spouses for a specific family member.
     */
    public function index(Family $family, FamilyMember $familyMember)
    {
        if ($familyMember->family_id !== $family->id || ($family->user_id !== Auth::id() && !$family->is_public)) {
            return response()->json(["message" => "Unauthorized or member not in family"], 403);
        }

        // $spouses = $familyMember->spouses()->get(); // Get all spouse records
        // The 'spouses' relationship in FamilyMember model already fetches related FamilyMember records.
        $spouses = $familyMember->spouses; 
        return view("admin.pages.spouses.index", compact("family", "familyMember", "spouses"));
        // return response()->json(["family_member" => $familyMember->load("spouses"), "family" => $family]); // Placeholder
    }

    /**
     * Show the form for creating a new spouse relationship for a family member.
     */
    public function create(Family $family, FamilyMember $familyMember)
    {
        if ($familyMember->family_id !== $family->id || $family->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        // Potential spouses are other members of the SAME family, excluding the member themselves and existing spouses.
        $existingSpouseIds = $familyMember->spouses()->pluck("family_members.id")->toArray();
        $potentialSpouses = $family->familyMembers()
            ->where("id", "!=", $familyMember->id)
            ->whereNotIn("id", $existingSpouseIds)
            // Potentially add gender considerations if needed, e.g. opposite gender for marriage
            // ->where("gender", "!=", $familyMember->gender) 
            ->get(["id", "full_name"]);

        return view("admin.pages.spouses.create", compact("family", "familyMember", "potentialSpouses"));
        // return response()->json(["family" => $family, "family_member" => $familyMember, "potential_spouses" => $potentialSpouses]); // Placeholder
    }

    /**
     * Store a newly created spouse relationship in storage.
     */
    public function store(Request $request, Family $family, FamilyMember $familyMember)
    {
        if ($familyMember->family_id !== $family->id || $family->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $validatedData = $request->validate([
            "spouse_id" => [
                "required",
                "exists:family_members,id",
                Rule::exists("family_members", "id")->where(function ($query) use ($family) {
                    $query->where("family_id", $family->id);
                }),
                Rule::notIn([$familyMember->id]), // Cannot marry oneself
                // Ensure not already married to this person (if status is married)
                Rule::unique("spouses", "family_member_id2")->where(function ($query) use ($familyMember) {
                    return $query->where("family_member_id1", $familyMember->id);
                }),
                Rule::unique("spouses", "family_member_id1")->where(function ($query) use ($familyMember) {
                    return $query->where("family_member_id2", $familyMember->id);
                }),
            ],
            "marriage_date" => "nullable|date",
            "marriage_location" => "nullable|string|max:255",
            "status" => ["required", Rule::in(["married", "divorced", "widowed"])],
        ]);

        // Attach the spouse relationship
        // The Spouse model is a Pivot model, so we interact via the belongsToMany relationship
        $familyMember->spouses()->attach($validatedData["spouse_id"], [
            "marriage_date" => $validatedData["marriage_date"],
            "marriage_location" => $validatedData["marriage_location"],
            "status" => $validatedData["status"],
        ]);

        return redirect()->route("admin.families.family_members.spouses.index", [$family, $familyMember])->with("success", "Spouse added successfully.");
        // return response()->json(["message" => "Spouse added"], 201); // Placeholder
    }

    /**
     * Show the form for editing a spouse relationship.
     * We edit the pivot data (marriage_date, status, etc.)
     */
    public function edit(Family $family, FamilyMember $familyMember, FamilyMember $spouse // The other family member in the relationship
    )
    {
        if ($familyMember->family_id !== $family->id || $spouse->family_id !== $family->id || $family->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized or members not in the same family"], 403);
        }

        $relationship = $familyMember->spouses()->where("family_members.id", $spouse->id)->first();

        if (!$relationship || !$relationship->pivot) {
            return response()->json(["message" => "Spouse relationship not found"], 404);
        }

        return view("admin.pages.spouses.edit", compact("family", "familyMember", "spouse", "relationship"));
        // return response()->json(["family" => $family, "family_member" => $familyMember, "spouse" => $spouse, "relationship_pivot_data" => $relationship->pivot]); // Placeholder
    }

    /**
     * Update the specified spouse relationship in storage.
     */
    public function update(Request $request, Family $family, FamilyMember $familyMember, FamilyMember $spouse)
    {
        if ($familyMember->family_id !== $family->id || $spouse->family_id !== $family->id || $family->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized or members not in the same family"], 403);
        }

        $validatedData = $request->validate([
            "marriage_date" => "nullable|date",
            "marriage_location" => "nullable|string|max:255",
            "status" => ["required", Rule::in(["married", "divorced", "widowed"])],
        ]);

        // Update pivot data
        $familyMember->spouses()->updateExistingPivot($spouse->id, [
            "marriage_date" => $validatedData["marriage_date"],
            "marriage_location" => $validatedData["marriage_location"],
            "status" => $validatedData["status"],
        ]);

        return redirect()->route("admin.families.family_members.spouses.index", [$family, $familyMember])->with("success", "Spouse relationship updated successfully.");
        // return response()->json(["message" => "Spouse relationship updated"]); // Placeholder
    }

    /**
     * Remove the specified spouse relationship from storage.
     */
    public function destroy(Family $family, FamilyMember $familyMember, FamilyMember $spouse)
    {
        if ($familyMember->family_id !== $family->id || $spouse->family_id !== $family->id || $family->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized or members not in the same family"], 403);
        }

        // Detach the spouse relationship
        $familyMember->spouses()->detach($spouse->id);

        return redirect()->route("admin.families.family_members.spouses.index", [$family, $familyMember])->with("success", "Spouse relationship removed successfully.");
        // return response()->json(null, 204); // Placeholder
    }
}

