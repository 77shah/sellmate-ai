<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TermsCondition;

class TermsConditionController extends Controller
{
     public function index()
    {
        $term = TermsCondition::first();
        return view('settings.term_conditions', compact('term'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $term = TermsCondition::first();

        if ($term) {
            $term->update(['content' => $request->content]);
        } else {
            TermsCondition::create(['content' => $request->content]);
        }

        return back()->with('success', 'Terms & Conditions updated successfully!');
    }
}
