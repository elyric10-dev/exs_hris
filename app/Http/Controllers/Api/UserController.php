<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


use App\Models\User;
use App\Models\PersonalInformation;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return $users;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // USER DATA
            'user_role_id' => 'required|max:2',
            'manager_id' => 'nullable|max:2',
            // 'created_by_user_id' => 'required|max:2',
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',

            // PERSONAL INFORMATION DATA
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'date_of_birth' => 'nullable|date',
        ]);

        $creator_id = User::where('id', Auth::user()->id)->where('user_role_id', 1)->orWhere('user_role_id', 2)->first()->id;

        $user = User::create([
            'user_role_id' => $request->user_role_id,
            'manager_id' => $creator_id,
            'created_by_user_id' => $creator_id,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $personal_information = PersonalInformation::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'personal_information' => $personal_information,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return response()->json(['message' => 'User updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
