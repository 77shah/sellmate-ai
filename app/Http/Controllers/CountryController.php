<?php
// app/Http/Controllers/CountryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('id', 'desc')->get();
        return view('location.country', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries',
            'code' => 'nullable|string|max:10'
        ]);

        Country::create([
            'name' => $request->name,
            'code' => $request->code,
            'status' => 1
        ]);

        return back()->with('success', 'Country added successfully!');
    }

    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,'.$id,
            'code' => 'nullable|string|max:10'
        ]);

        $country->update([
            'name' => $request->name,
            'code' => $request->code
        ]);

        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $country = Country::findOrFail($id);
        $country->status = !$country->status;
        $country->save();

        return response()->json(['success' => true, 'status' => $country->status]);
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);
        
        if($country->states()->count() > 0) {
            return back()->with('error', 'Cannot delete country! First delete its states.');
        }
        
        $country->delete();
        return back()->with('success', 'Country deleted successfully!');
    }
}