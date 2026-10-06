<?php
// app/Http/Controllers/StateController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;

class StateController extends Controller
{
    public function index()
    {
        $countries = Country::where('status', 1)->orderBy('name')->get();
        $states = State::with('country')->orderBy('id', 'desc')->get();
        return view('location.state', compact('countries', 'states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id'
        ]);

        State::create([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'status' => 1
        ]);

        return back()->with('success', 'State added successfully!');
    }

    public function update(Request $request, $id)
    {
        $state = State::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id'
        ]);

        $state->update([
            'name' => $request->name,
            'country_id' => $request->country_id
        ]);

        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $state = State::findOrFail($id);
        $state->status = !$state->status;
        $state->save();

        return response()->json(['success' => true, 'status' => $state->status]);
    }

    public function destroy($id)
    {
        $state = State::findOrFail($id);
        
        if($state->cities()->count() > 0) {
            return back()->with('error', 'Cannot delete state! First delete its cities.');
        }
        
        $state->delete();
        return back()->with('success', 'State deleted successfully!');
    }
}