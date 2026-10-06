<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
   // app/Http/Controllers/DashboardController.php

public function index()
{
    // 🔴 Debug - Check karein ye function call ho raha hai
    \Log::info('DashboardController@index called');
    \Log::info('User:', ['user' => Auth::user()]);
    
    $user = Auth::user();
    
    $data = [
        'user' => $user,
        'totalCategories' => \DB::table('categories')->count(),
        'totalSubCategories' => \DB::table('sub_categories')->count(),
        'totalUsers' => \DB::table('users')->count(),
    ];
    
    return view('dashboard', $data);
}
}