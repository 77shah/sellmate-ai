<?php
// app/Http/Controllers/StaffController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    // ===== DASHBOARD =====
    public function dashboard()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $staffId = Auth::id();
        
        $data = [
            'assignedCustomers' => Customer::where('tenant_id', $tenantId)
                ->where('assigned_to', $staffId)
                ->count(),
            'totalOrders' => Order::where('tenant_id', $tenantId)
                ->where('assigned_to', $staffId)
                ->count(),
            'pendingOrders' => Order::where('tenant_id', $tenantId)
                ->where('assigned_to', $staffId)
                ->where('status', 'pending')
                ->count(),
            'activeConversations' => Conversation::where('tenant_id', $tenantId)
                ->where('assigned_to', $staffId)
                ->where('status', 'open')
                ->count(),
        ];
        return view('staff.dashboard', $data);
    }

    // ===== INBOX (Only assigned customers) =====
    public function inbox(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $staffId = Auth::id();
        
        $query = Conversation::with('customer')
            ->where('tenant_id', $tenantId)
            ->where('assigned_to', $staffId);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $conversations = $query->latest()->paginate(20);
        return view('staff.inbox', compact('conversations'));
    }

    // ===== ORDERS (View only) =====
    public function orders(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $staffId = Auth::id();
        
        $query = Order::with('customer')
            ->where('tenant_id', $tenantId)
            ->where('assigned_to', $staffId);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->latest()->paginate(20);
        return view('staff.orders', compact('orders'));
    }

    // ===== CRM (View only) =====
    public function crm(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $staffId = Auth::id();
        
        $query = Customer::where('tenant_id', $tenantId)
            ->where('assigned_to', $staffId);
        
        if ($request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('phone', 'LIKE', "%{$request->search}%");
        }
        
        $customers = $query->latest()->paginate(15);
        return view('staff.crm', compact('customers'));
    }
}