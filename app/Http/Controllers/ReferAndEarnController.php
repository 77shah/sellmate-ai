<?php
// app/Http/Controllers/ReferAndEarnController.php

namespace App\Http\Controllers;

use App\Models\ReferAndEarn;
use Illuminate\Http\Request;

class ReferAndEarnController extends Controller
{
    public function index()
    {
        $refer = ReferAndEarn::first();
        return view('settings.refer', compact('refer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        ReferAndEarn::updateOrCreate(
            ['id' => 1],
            ['content' => $request->content]
        );

        return redirect()->route('refer.index')->with('success', 'Refer & Earn content updated successfully!');
    }
}