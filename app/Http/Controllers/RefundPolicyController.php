<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RefundPolicy;

class RefundPolicyController extends Controller
{
    public function index()
    {
        $refund = RefundPolicy::first();
        return view('settings.refund_policy', compact('refund'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required'
        ]);

        $refund = RefundPolicy::first();

        if ($refund) {
            $refund->update(['content' => $request->content]);
        } else {
            RefundPolicy::create(['content' => $request->content]);
        }

        return back()->with('success', 'Refund Policy updated successfully!');
    }
}
