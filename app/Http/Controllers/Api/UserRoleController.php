<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserRole;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userRoles = UserRole::all();

        return response()->json($userRoles, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // CREATE USER ROLE
        $request->validate([
            'role_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $userRole = UserRole::create([
            'role_name' => $request->role_name,
            'description' => $request->description,
        ]);

        $userRole->logs()->create([
            'action' => 'create',
            'details' => 'User role created successfully',
        ]);

        return response()->json([
            'message' => 'User role created successfully',
            'user_role' => $userRole
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $userRole = UserRole::findOrFail($id);

        return response()->json($userRole, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $userRole = UserRole::findOrFail($id);

        $userRole->update($request->all());

        return response()->json([
            'message' => 'User role updated successfully',
            'user_role' => $userRole
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userRole = UserRole::findOrFail($id);

        $userRole->delete();

        return response()->json([
            'message' => 'User role deleted successfully',
            'user_role' => $userRole
        ], 200);
    }
}
