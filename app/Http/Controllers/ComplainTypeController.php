<?php
// app/Http/Controllers/ComplainTypeController.php

namespace App\Http\Controllers;

use App\Models\ComplainType;
use Illuminate\Http\Request;

class ComplainTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $complainTypes = ComplainType::latest()->get();
        return view('settings.add_complaintype', compact('complainTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:complain_types,name',
            'description' => 'nullable|string',
            // Remove status validation since it will be active by default
        ]);

        // Create with default active status
        ComplainType::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'active' // Default value
        ]);

        return redirect()->route('complain-types.index')->with('success', 'Complain type created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:complain_types,name,' . $id,
            'description' => 'nullable|string',
            // Remove status validation since it will be active by default
        ]);

        $complainType = ComplainType::findOrFail($id);
        $complainType->update([
            'name' => $request->name,
            'description' => $request->description
            // Keep existing status
        ]);

        return redirect()->route('complain-types.index')->with('success', 'Complain type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $complainType = ComplainType::findOrFail($id);
        $complainType->delete();

        return redirect()->route('complain-types.index')->with('success', 'Complain type deleted successfully.');
    }

    /**
     * Get complain type for editing.
     */
    public function edit($id)
    {
        $complainType = ComplainType::findOrFail($id);
        return response()->json($complainType);
    }

    /**
     * Toggle status of complain type.
     */
    public function toggleStatus($id)
    {
        $complainType = ComplainType::findOrFail($id);
        $complainType->status = $complainType->status === 'active' ? 'inactive' : 'active';
        $complainType->save();

        return redirect()->route('complain-types.index')->with('success', 'Status updated successfully.');
    }
}