<?php
// app/Http/Controllers/SuperAdmin/UserManagementController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('super-admin.users.index', compact('users'));
    }

    public function create()
    {
        $plans = Plan::where('is_active', true)->get();
        return view('super-admin.users.create', compact('plans'));
    }

    /**
     * 🔥 FIXED: UUID column hatao
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'mobile_no' => 'nullable|string|max:20',
            'whatsapp_no' => 'nullable|string|max:20',
            'type' => 'required|in:SuperAdmin,Admin,Owner,Staff,SalesAgent',
            'status' => 'required|in:active,inactive',
            'current_plan_id' => 'nullable|exists:plans,id',
        ]);

        // Generate tenant_id for Owner
        $tenantId = null;
        if ($request->type == 'Owner') {
            $tenantId = 'tenant_' . Str::random(6);
        }

        // 🔥 UUID HATAO - sirf required fields daalo
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile_no' => $request->mobile_no,
            'whatsapp_no' => $request->whatsapp_no,
            'type' => $request->type,
            'status' => $request->status,
            'tenant_id' => $tenantId,
            'current_plan_id' => $request->current_plan_id,
        ]);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User "' . $user->name . '" created successfully!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $plans = Plan::where('is_active', true)->get();
        return view('super-admin.users.edit', compact('user', 'plans'));
    }

    /**
     * 🔥 FIXED: UUID column hatao
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'mobile_no' => 'nullable|string|max:20',
            'whatsapp_no' => 'nullable|string|max:20',
            'type' => 'required|in:SuperAdmin,Admin,Owner,Staff,SalesAgent',
            'status' => 'required|in:active,inactive',
            'current_plan_id' => 'nullable|exists:plans,id',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_no' => $request->mobile_no,
            'whatsapp_no' => $request->whatsapp_no,
            'type' => $request->type,
            'status' => $request->status,
            'current_plan_id' => $request->current_plan_id,
        ];

        // Update tenant_id if type is Owner
        if ($request->type == 'Owner' && empty($user->tenant_id)) {
            $data['tenant_id'] = 'tenant_' . Str::random(6);
        } elseif ($request->type != 'Owner') {
            $data['tenant_id'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User "' . $user->name . '" updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->type == 'SuperAdmin') {
            return back()->with('error', 'Cannot delete Super Admin user.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('super-admin.users.index')
            ->with('success', 'User "' . $userName . '" deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->type == 'SuperAdmin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change Super Admin status.'
            ], 403);
        }

        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();

        return response()->json([
            'success' => true,
            'status' => $user->status,
            'message' => 'Status updated successfully!'
        ]);
    }
}