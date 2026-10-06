<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutUs;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = AboutUs::first();
        return view('settings.about_us', compact('about'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        $about = AboutUs::first();

        if ($about) {
            $about->update(['content' => $request->content]);
        } else {
            AboutUs::create(['content' => $request->content]);
        }

        return back()->with('success', 'About Us updated successfully!');
    }
}
