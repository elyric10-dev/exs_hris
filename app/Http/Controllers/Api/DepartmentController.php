<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();

        return response()->json($departments, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'manager_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $deparment = Department::create([
            'manager_id' => $request->manager_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Department created successfully',
            'department' => $deparment,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::findOrFail($id);

        return response()->json($department, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $department->update($request->all());

        return response()->json([
            'message' => 'Department updated successfully',
            'department' => $department,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Department deleted successfully',
            'department' => $department,
        ], 200);
    }
}
