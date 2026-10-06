<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\DataEntryUser;
use App\Models\User;


class UserController extends Controller
{
     public function profile()
    {
        // Check which user is logged in
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('data_entry')->check()) {
            $user = Auth::guard('data_entry')->user();
        } else {
            return redirect()->route('login');
        }

        // Single view for both users
        return view('settings.profile', compact('user'));
    }

    // Update profile
    public function profileUpdate(Request $request)
    {
        // Get current user based on guard
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $userType = 'admin';
        } elseif (Auth::guard('data_entry')->check()) {
            $user = Auth::guard('data_entry')->user();
            $userType = 'data_entry';
        } else {
            return redirect()->route('login');
        }

        // Common validation for both users
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:'.$user->getTable().',email,'.$user->id,
        ];

        // Add specific validations based on user type
        if ($userType == 'admin') {
            $validationRules['mobile_no'] = 'nullable|string|max:20';
            $validationRules['whatsapp_no'] = 'nullable|string|max:20';
        } else {
            $validationRules['mobile'] = 'required|string|max:20';
        }

        $request->validate($validationRules);

        // Prepare update data based on user type
        if ($userType == 'admin') {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'whatsapp_no' => $request->whatsapp_no,
            ];
        } else {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
            ];
        }

        // Update user
        $user->update($updateData);

        return back()->with('success', 'Profile updated successfully.');
    }

    // Show change password page
    public function changePassword()
    {
        // Single view for both users
        return view('settings.change-password');
    }

    // Update password (Without current password check)
    public function updatePassword(Request $request)
    {
        // Validate only new password
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Get current user based on guard
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('data_entry')->check()) {
            $user = Auth::guard('data_entry')->user();
        } else {
            return redirect()->route('login');
        }

        // Directly update password without checking current password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
