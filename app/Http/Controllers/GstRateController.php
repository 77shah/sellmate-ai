<?php
// app/Http/Controllers/GstRateController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GstRate;

class GstRateController extends Controller
{
    public function index()
    {
        $gstRates = GstRate::orderBy('id', 'desc')->get();
        return view('settings.gst', compact('gstRates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'required|string|max:10'
        ]);

        GstRate::create([
            'rate' => $request->rate,
            'status' => 1
        ]);

        return back()->with('success', 'GST rate added successfully!');
    }

    public function update(Request $request, $id)
    {
        $gst = GstRate::findOrFail($id);
        
        $request->validate([
            'rate' => 'required|string|max:10'
        ]);

        $gst->update([
            'rate' => $request->rate
        ]);

        return back()->with('success', 'GST rate updated successfully!');
    }

    public function toggleStatus($id)
    {
        $gst = GstRate::findOrFail($id);
        $gst->status = !$gst->status;
        $gst->save();

        return response()->json([
            'success' => true,
            'status' => $gst->status,
            'message' => 'Status changed successfully!'
        ]);
    }

    public function destroy($id)
    {
        $gst = GstRate::findOrFail($id);
        $gst->delete();

        return back()->with('success', 'GST rate deleted successfully!');
    }
}