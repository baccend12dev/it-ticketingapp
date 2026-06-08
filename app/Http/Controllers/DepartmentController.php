<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the departments.
     */
    public function index()
    {
        $departments = Department::orderBy('id')->get();
        return view('departments.index', compact('departments'));
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department = Department::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : true,
        ]);

        return redirect()->back()->with('success', "Department '{$department->name}' has been successfully created.");
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : false,
        ]);

        return redirect()->back()->with('success', "Department '{$department->name}' has been successfully updated.");
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department)
    {
        $name = $department->name;
        $department->delete();

        return redirect()->back()->with('success', "Department '{$name}' has been successfully deleted.");
    }
}
