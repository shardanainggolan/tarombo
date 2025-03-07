<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Set active menu state
        session(['activeMenu' => 'listUsers']);
        session(['activeParentMenu' => 'users']);
        session(['activeSubParentMenu' => '']);
        if ($request->ajax()) {
            $data = User::with('person.marga')->latest();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('person_info', function ($row) {
                    if ($row->person) {
                        return '<span class="badge bg-success">' . 
                            $row->person->first_name . ' ' . $row->person->last_name . 
                            ' (' . $row->person->marga->name . ')' .
                            '</span>';
                    }
                    return '<span class="badge bg-secondary">Tidak terhubung</span>';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div class="d-flex align-items-center">
                            <a href="' . route('admin.users.edit', $row->id) . '" class="btn btn-icon btn-label-info waves-effect me-2">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <button type="button" id="' . $row->id . '" class="delete-record btn btn-icon btn-label-danger waves-effect">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'person_info'])
                ->make(true);
        }
        
        return view('admin.pages.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Set active menu state
        session(['activeMenu' => 'createUser']);
        session(['activeParentMenu' => 'users']);
        session(['activeSubParentMenu' => '']);
        // Get people who aren't already linked to a user
        $availablePeople = Person::whereDoesntHave('user')->orderBy('first_name')->get();
        
        return view('admin.pages.users.create', compact('availablePeople'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'person_id' => 'nullable|exists:people,id',
        ]);

        // Check if the person is already linked to a user
        if ($request->has('person_id') && $request->person_id) {
            $existingUser = User::where('person_id', $request->person_id)->first();
            if ($existingUser) {
                return back()->withErrors(['person_id' => 'Orang ini sudah terhubung dengan pengguna lain.'])->withInput();
            }
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'person_id' => $request->person_id,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Get people who aren't already linked to a user, or are linked to this user
        $availablePeople = Person::where(function($query) use ($user) {
                $query->whereDoesntHave('user')
                      ->orWhere('id', $user->person_id);
            })
            ->orderBy('first_name')
            ->get();
        
        return view('admin.pages.users.edit', compact('user', 'availablePeople'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'person_id' => 'nullable|exists:people,id',
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        // Check if the person is already linked to a different user
        if ($request->has('person_id') && $request->person_id) {
            $existingUser = User::where('person_id', $request->person_id)
                ->where('id', '!=', $user->id)
                ->first();
                
            if ($existingUser) {
                return back()->withErrors(['person_id' => 'Orang ini sudah terhubung dengan pengguna lain.'])->withInput();
            }
        }

        // Update user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'person_id' => $request->person_id,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Pengguna berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus pengguna. ' . $e->getMessage()
            ], 422);
        }
    }
}