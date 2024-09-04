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
        $users = User::with('personalInformation')->get();

        $data = [
            'users' => $users
        ];

        return response()->json([
            'error' => 0,
            'message' => 'User created successfully',
            'data' => $data
        ]);
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
            'email' => $request->email,
        ]);

        PersonalInformation::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
        ]);

        if ($user->personalInformation) {
            $data = [
                'users' => $user,
            ];
        }

        return response()->json([
            'error' => 0,
            'message' => 'User created successfully',
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if ($user) {
            if ($user->personalInformation) {
                $data = [
                    'users' => $user,
                ];
            }

            return response()->json([
                'error' => 0,
                'message' => 'User created successfully',
                'data' => $data
            ]);
        } else
            return response()->json([
                'error' => 1,
                'message' => "Can't find User"
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if ($user) {
            $personal_information = $user->personalInformation;

            if ($personal_information) {
                $user->update($request->all());
                $personal_information->update($request->all());

                return response()->json([
                    'error' => 0,
                    'message' => "User Personal Information Updated Successfully!",
                    'data' => $user
                ]);
            } else {

                return response()->json([
                    'error' => 0,
                    'message' => "User Updated Successfully!",
                    'data' => $user
                ]);
            }
        } else {

            return response()->json([
                'error' => 1,
                'message' => "Can't find User"
            ]);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if ($user) {
            if ($user->personalInformation)
                $user->personalInformation->delete();
            $user->delete();

            return response()->json([
                'error' => 0,
                'message' => "User Deleted Successfully!",
                'data' => $user
            ]);
        } else {
            return response()->json([
                'error' => 1,
                'message' => "Can't find User"
            ]);
        }


    }
}
