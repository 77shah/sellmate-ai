<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Middleware\TrackLoginAttempts;

class AuthController extends Controller
{
    // ============================================
    // 1. DEFAULT LOGIN (Admin / Owner)
    // ============================================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // 🔴 Sirf Admin aur Owner ko allow karein
            if (!in_array($user->type, ['Admin', 'Owner'])) {
                Auth::logout();
                // 🔥 Track failed login
                app(TrackLoginAttempts::class)->failed($request);
                return redirect()->route('login')->with('error', 'Unauthorized access. Please use correct login portal.');
            }
            
            if ($user->status == 'inactive') {
                Auth::logout();
                // 🔥 Track failed login
                app(TrackLoginAttempts::class)->failed($request);
                return redirect()->route('login')->with('error', 'Account is inactive.');
            }

            // 🔥 Track successful login
            app(TrackLoginAttempts::class)->login($request, $user);

            $user->update(['last_login_at' => now()]);
            return $this->redirectBasedOnRole($user);
        }

        // 🔥 Track failed login
        app(TrackLoginAttempts::class)->failed($request);

        return back()->with('error', 'Invalid email or password.');
    }

    // ============================================
    // 2. SUPER ADMIN LOGIN
    // ============================================
    public function showSuperAdminLoginForm()
    {
        return view('auth.super-admin-login');
    }

    public function superAdminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // 🔴 Sirf SuperAdmin ko allow karein
            if ($user->type !== 'SuperAdmin') {
                Auth::logout();
                // 🔥 Track failed login
                app(TrackLoginAttempts::class)->failed($request);
                return redirect()->route('super-admin.login')->with('error', 'Unauthorized access. Please use correct login portal.');
            }
            
            if ($user->status == 'inactive') {
                Auth::logout();
                // 🔥 Track failed login
                app(TrackLoginAttempts::class)->failed($request);
                return redirect()->route('super-admin.login')->with('error', 'Account is inactive.');
            }

            // 🔥 Track successful login
            app(TrackLoginAttempts::class)->login($request, $user);

            $user->update(['last_login_at' => now()]);
            return redirect()->route('super-admin.dashboard');
        }

        // 🔥 Track failed login
        app(TrackLoginAttempts::class)->failed($request);

        return back()->with('error', 'Invalid email or password.');
    }

    // ============================================
    // 3. STAFF LOGIN
    // ============================================
    public function showStaffLoginForm()
    {
        return view('auth.staff-login');
    }

    public function staffLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // 🔴 Sirf Staff aur SalesAgent ko allow karein
            if (!in_array($user->type, ['Staff', 'SalesAgent'])) {
                Auth::logout();
                // 🔥 Track failed login
                app(TrackLoginAttempts::class)->failed($request);
                return redirect()->route('staff.login')->with('error', 'Unauthorized access. Please use correct login portal.');
            }
            
            if ($user->status == 'inactive') {
                Auth::logout();
                // 🔥 Track failed login
                app(TrackLoginAttempts::class)->failed($request);
                return redirect()->route('staff.login')->with('error', 'Account is inactive.');
            }

            // 🔥 Track successful login
            app(TrackLoginAttempts::class)->login($request, $user);

            $user->update(['last_login_at' => now()]);
            return redirect()->route('staff.dashboard');
        }

        // 🔥 Track failed login
        app(TrackLoginAttempts::class)->failed($request);

        return back()->with('error', 'Invalid email or password.');
    }

    // ============================================
    // 4. LOGOUT - Role Based Redirect
    // ============================================
    public function logout()
    {
        $user = Auth::user();
        $userType = $user->type ?? 'Guest';
        
        Auth::logout();
        
        // 🔴 Role-based logout redirect
        switch($userType) {
            case 'SuperAdmin':
                return redirect()->route('super-admin.login')->with('success', 'You have been logged out.');
            case 'Staff':
            case 'SalesAgent':
                return redirect()->route('staff.login')->with('success', 'You have been logged out.');
            case 'Admin':
            case 'Owner':
            default:
                return redirect()->route('login')->with('success', 'You have been logged out.');
        }
    }

    // ============================================
    // 5. REDIRECT BASED ON ROLE
    // ============================================
    protected function redirectBasedOnRole($user)
    {
        switch($user->type) {
            case 'SuperAdmin':
                return redirect()->route('super-admin.dashboard');
            case 'Admin':
                return redirect()->route('dashboard');
            case 'Owner':
                return redirect()->route('owner.dashboard');
            case 'Staff':
            case 'SalesAgent':
                return redirect()->route('staff.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }
}