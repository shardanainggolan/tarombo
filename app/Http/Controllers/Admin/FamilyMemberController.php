<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use App\Models\Family;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FamilyMemberController extends Controller
{
    /**
     * Display a listing of the resource for a specific family.
     */
    public function index(Family $family)
    {
        // Authorize: Ensure user can view this family's members
        if ($family->user_id !== Auth::id() && !$family->is_public) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $familyMembers = $family->familyMembers()->with(["father", "mother", "currentSpouses"])->paginate(15);
        return view("admin.pages.family_members.index", compact("family", "familyMembers"));
        // return response()->json(["family" => $family, "family_members" => $familyMembers]); // Placeholder
    }

    /**
     * Show the form for creating a new resource within a family.
     */
    public function create(Family $family)
    {
        // Authorize: Ensure user can add members to this family
        if ($family->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized to add member to this family"], 403);
        }
        // For father/mother selection, get existing members of the same family
        $potentialParents = $family->familyMembers()->get(["id", "full_name", "gender"]);
        $users = User::all(["id", "name"]); // For linking to a user account

        return view("admin.pages.family_members.create", compact("family", "potentialParents", "users"));
        // return response()->json(["family" => $family, "potential_parents" => $potentialParents, "users" => $users]); // Placeholder
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Family $family)
    {
        if ($family->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized to add member to this family"], 403);
        }

        $validatedData = $request->validate([
            "full_name" => "required|string|max:255",
            "nickname" => "nullable|string|max:255",
            "gender" => ["required", Rule::in(["male", "female", "other"])],
            "birth_date" => "nullable|date",
            "birth_place" => "nullable|string|max:255",
            "death_date" => "nullable|date|after_or_equal:birth_date",
            "death_place" => "nullable|string|max:255",
            "father_id" => ["nullable", "exists:family_members,id", Rule::exists("family_members", "id")->where(function ($query) use ($family) {
                $query->where("family_id", $family->id)->where("gender", "male");
            })],
            "mother_id" => ["nullable", "exists:family_members,id", Rule::exists("family_members", "id")->where(function ($query) use ($family) {
                $query->where("family_id", $family->id)->where("gender", "female");
            })],
            "order_in_siblings" => "nullable|integer|min:1",
            "bio" => "nullable|string",
            "profile_picture_path" => "nullable|string|max:255", // Later, this could be a file upload
            "user_account_id" => "nullable|exists:users,id|unique:family_members,user_account_id",
        ]);

        $validatedData["family_id"] = $family->id;
        $familyMember = FamilyMember::create($validatedData);

        return redirect()->route("admin.families.family_members.index", $family)->with("success", "Family member added successfully.");
        // return response()->json($familyMember, 201); // Placeholder
    }

    /**
     * Display the specified resource.
     */
    public function show(Family $family, FamilyMember $familyMember)
    {
        if ($familyMember->family_id !== $family->id || ($family->user_id !== Auth::id() && !$family->is_public) ) {
             return response()->json(["message" => "Unauthorized or member not in family"], 403);
        }
        $familyMember->load(["father", "mother", "children", "currentSpouses", "userAccount"]);
        return view("admin.pages.family_members.show", compact("family", "familyMember"));
        // return response()->json($familyMember); // Placeholder
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Family $family, FamilyMember $familyMember)
    {
        if ($familyMember->family_id !== $family->id || $family->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized or member not in family"], 403);
        }
        $potentialParents = $family->familyMembers()->where("id", "!=", $familyMember->id)->get(["id", "full_name", "gender"]);
        $users = User::all(["id", "name"]);

        return view("admin.pages.family_members.edit", compact("family", "familyMember", "potentialParents", "users"));
        // return response()->json(["family" => $family, "family_member" => $familyMember, "potential_parents" => $potentialParents, "users" => $users]); // Placeholder
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Family $family, FamilyMember $familyMember)
    {
         if ($familyMember->family_id !== $family->id || $family->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized or member not in family"], 403);
        }

        $validatedData = $request->validate([
            "full_name" => "required|string|max:255",
            "nickname" => "nullable|string|max:255",
            "gender" => ["required", Rule::in(["male", "female", "other"])],
            "birth_date" => "nullable|date",
            "birth_place" => "nullable|string|max:255",
            "death_date" => "nullable|date|after_or_equal:birth_date",
            "death_place" => "nullable|string|max:255",
            "father_id" => ["nullable", "exists:family_members,id", Rule::exists("family_members", "id")->where(function ($query) use ($family) {
                $query->where("family_id", $family->id)->where("gender", "male");
            }), Rule::notIn([$familyMember->id])],
            "mother_id" => ["nullable", "exists:family_members,id", Rule::exists("family_members", "id")->where(function ($query) use ($family) {
                $query->where("family_id", $family->id)->where("gender", "female");
            }), Rule::notIn([$familyMember->id])],
            "order_in_siblings" => "nullable|integer|min:1",
            "bio" => "nullable|string",
            "profile_picture_path" => "nullable|string|max:255",
            "user_account_id" => ["nullable", "exists:users,id", Rule::unique("family_members", "user_account_id")->ignore($familyMember->id)],
        ]);

        $familyMember->update($validatedData);
        return redirect()->route("admin.families.family_members.show", [$family, $familyMember])->with("success", "Family member updated successfully.");
        // return response()->json($familyMember); // Placeholder
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Family $family, FamilyMember $familyMember)
    {
        if ($familyMember->family_id !== $family->id || $family->user_id !== Auth::id()) {
            return response()->json(["message" => "Unauthorized or member not in family"], 403);
        }

        // Add logic here to handle re-parenting children or other cleanup if necessary
        $familyMember->delete();
        return redirect()->route("admin.families.family_members.index", $family)->with("success", "Family member deleted successfully.");
        // return response()->json(null, 204); // Placeholder
    }
}

