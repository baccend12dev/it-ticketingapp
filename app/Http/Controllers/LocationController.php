<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the locations.
     */
    public function index()
    {
        $locations = Location::orderBy('id')->get();
        return view('locations.index', compact('locations'));
    }

    /**
     * Store a newly created location in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $location = Location::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : true,
        ]);

        return redirect()->back()->with('success', "Location '{$location->name}' has been successfully created.");
    }

    /**
     * Update the specified location in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $location->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : false,
        ]);

        return redirect()->back()->with('success', "Location '{$location->name}' has been successfully updated.");
    }

    /**
     * Remove the specified location from storage.
     */
    public function destroy(Location $location)
    {
        $name = $location->name;
        $location->delete();

        return redirect()->back()->with('success', "Location '{$name}' has been successfully deleted.");
    }
}
