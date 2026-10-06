<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ContactUsController extends Controller
{
    public function index()
    {
        $user = User::first();
        return view('settings.contact-us', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'mobile_no' => 'required|string|max:15',
            'whatsapp_no' => 'required|string|max:15',
            'email' => 'required|email|max:255',
        ]);

        $user = User::first();

        if ($user) {
            $user->mobile_no = $request->mobile_no;
            $user->whatsapp_no = $request->whatsapp_no;
            $user->email = $request->email;
            $user->save();
        }

        return redirect()->back()->with('success', 'Contact details updated successfully!');
    }
}
