<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;

class PrivacyPolicyController extends Controller
{
     public function index()
    {
        $privacy = PrivacyPolicy::first();
        return view('settings.privacy_policy', compact('privacy'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        $privacy = PrivacyPolicy::first();

        if ($privacy) {
            $privacy->update(['content' => $request->content]);
        } else {
            PrivacyPolicy::create(['content' => $request->content]);
        }

        return back()->with('success', 'Privacy Policy updated successfully!');
    }
}
