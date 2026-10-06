<?php
// app/Http/Controllers/CityController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CityController extends Controller
{
    public function index()
    {
        $countries = Country::where('status', 1)->orderBy('name')->get();
        $cities = City::with('state.country')->orderBy('id', 'desc')->get();
        return view('location.city', compact('countries', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id'
        ]);

        City::create([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'status' => 1
        ]);

        return back()->with('success', 'City added successfully!');
    }

    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id'
        ]);

        $city->update([
            'name' => $request->name,
            'state_id' => $request->state_id
        ]);

        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $city = City::findOrFail($id);
        $city->status = !$city->status;
        $city->save();

        return response()->json(['success' => true, 'status' => $city->status]);
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        return back()->with('success', 'City deleted successfully!');
    }

    // Get states by country (AJAX)
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)->where('status', 1)->get();
        return response()->json($states);
    }
}