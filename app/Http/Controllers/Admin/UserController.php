<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar pengguna
    public function index()
    {
        $users = User::all();  // Anda bisa menambahkan pagination jika diperlukan
        return view('admin.pages.users.index', compact('users'));
    }

    // Menampilkan form untuk membuat pengguna baru
    public function create()
    {
        return view('admin.pages.users.create');
    }

    // Menyimpan pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,  // Defaultnya bisa 'user' atau sesuai input
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dibuat');
    }

    // Menampilkan form untuk mengedit pengguna
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pages.users.edit', compact('user'));
    }

    // Menyimpan perubahan data pengguna
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus');
    }
}
