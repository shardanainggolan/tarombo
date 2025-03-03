<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'person_id' => $user->person_id
                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'person_id' => 'nullable|exists:people,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if person_id is already associated with another user
        if ($request->has('person_id') && $request->person_id) {
            $existingUser = User::where('person_id', $request->person_id)->first();
            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'This person record is already linked to another user account'
                ], 422);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'person_id' => $request->person_id
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'person_id' => $user->person_id
                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Get user profile with linked person data
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        $personData = null;

        if ($user->person_id) {
            $person = Person::with('marga')->find($user->person_id);
            if ($person) {
                $personData = [
                    'id' => $person->id,
                    'first_name' => $person->first_name,
                    'last_name' => $person->last_name,
                    'gender' => $person->gender,
                    'birth_date' => $person->birth_date ? $person->birth_date->format('Y-m-d') : null,
                    'marga' => [
                        'id' => $person->marga->id,
                        'name' => $person->marga->name
                    ],
                    'photo_url' => $person->photo_url ? url(Storage::url($person->photo_url)) : null,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'person' => $personData
            ]
        ]);
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Link person record to user account
     */
    public function linkPersonRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'person_id' => 'required|exists:people,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Check if person_id is already associated with another user
        $existingUser = User::where('person_id', $request->person_id)
            ->where('id', '!=', $user->id)
            ->first();
        
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'This person record is already linked to another user account'
            ], 422);
        }

        $user->person_id = $request->person_id;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Person record linked successfully',
            'data' => [
                'user_id' => $user->id,
                'person_id' => $user->person_id
            ]
        ]);
    }
}
