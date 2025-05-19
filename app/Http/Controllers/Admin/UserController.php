<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Typically, only admins should see all users.
        // Add authorization logic here if needed.
        $users = User::with("currentFamily")->paginate(15);
        return view("admin.pages.users.index", compact("users"));
        // return response()->json($users); // Placeholder
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $families = Family::all(); // For selecting current_family_id
        return view("admin.pages.users.create", compact("families"));
        // return response()->json(["message" => "Show form to create user", "families" => $families]); // Placeholder
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:8|confirmed",
            "current_family_id" => "nullable|exists:families,id",
        ]);

        $validatedData["password"] = Hash::make($validatedData["password"]);
        $user = User::create($validatedData);

        return redirect()->route("admin.users.index")->with("success", "User created successfully.");
        // return response()->json($user, 201); // Placeholder
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(["families", "currentFamily", "familyMemberProfile"]);
        return view("admin.pages.users.show", compact("user"));
        // return response()->json($user); // Placeholder
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $families = Family::all();
        return view("admin.pages.users.edit", compact("user", "families"));
        // return response()->json(["user" => $user, "families" => $families]); // Placeholder
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            "name" => "required|string|max:255",
            "email" => ["required", "string", "email", "max:255", Rule::unique("users")->ignore($user->id)],
            "password" => "nullable|string|min:8|confirmed",
            "current_family_id" => "nullable|exists:families,id",
        ]);

        if (!empty($validatedData["password"])) {
            $validatedData["password"] = Hash::make($validatedData["password"]);
        } else {
            unset($validatedData["password"]); // Don't update password if not provided
        }

        $user->update($validatedData);
        return redirect()->route("admin.users.index")->with("success", "User updated successfully.");
        // return response()->json($user); // Placeholder
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Add logic to handle user deletion, e.g., reassigning families or deleting them based on policy.
        // For now, simple delete.
        // Be careful with deleting users, especially if they own critical data.
        if ($user->id === Auth::id()) {
            return redirect()->route("admin.users.index")->with("error", "You cannot delete your own account.");
            // return response()->json(["message" => "Cannot delete own account"], 403); // Placeholder
        }

        $user->delete();
        return redirect()->route("admin.users.index")->with("success", "User deleted successfully.");
        // return response()->json(null, 204); // Placeholder
    }
}

