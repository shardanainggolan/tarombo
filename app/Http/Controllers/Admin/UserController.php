<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('person')->latest()->get();
        return view('admin.pages.users.index', compact('users'));
    }

    public function create()
    {
        $availablePeople = Person::doesntHave('user')->get();
        return view('admin.pages.users.create', compact('availablePeople'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'person_id' => 'nullable|exists:people,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->person_id) {
            Person::find($request->person_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        $availablePeople = Person::doesntHave('user')
            ->orWhere('id', $user->person?->id)
            ->get();
            
        return view('admin.pages.users.edit', compact('user', 'availablePeople'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'person_id' => 'nullable|exists:people,id'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update person relationship
        if ($request->person_id) {
            // Remove previous connection
            if ($user->person) {
                $user->person->update(['user_id' => null]);
            }
            Person::find($request->person_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->person) {
            return back()->withErrors([
                'message' => 'Tidak bisa menghapus user yang terhubung dengan data orang'
            ]);
        }

        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
