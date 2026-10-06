<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SocialLink;

class SocialSettingController extends Controller
{
    public function index()
    {
        $socials = SocialLink::all();
        return view('settings.social_setting', [
            'socials' => $socials,
            'editData' => null,
            'editMode' => false,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'social_name' => 'required|string|max:255',
            'social_link' => 'required|url|max:500',
            'social_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $data = $request->only(['social_name', 'social_link']);
        $data['status'] = 'active';

        if ($request->hasFile('social_image')) {
            $image = $request->file('social_image');
            $name = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('admin_uploads'), $name);
            $data['social_image'] = $name;
        }

        SocialLink::create($data);

        return redirect()->route('social.index')->with('success', 'Social link added successfully!');
    }

    public function edit($id)
    {
        $editData = SocialLink::findOrFail($id);
        $socials = SocialLink::all();
        return view('settings.social_setting', [
            'editData' => $editData,
            'socials' => $socials,
            'editMode' => true,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'social_name' => 'required|string|max:255',
            'social_link' => 'required|url|max:500',
            'social_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $social = SocialLink::findOrFail($id);
        $data = $request->only(['social_name', 'social_link']);

        if ($request->hasFile('social_image')) {
            // Delete old image if exists
            if ($social->social_image && file_exists(public_path('admin_uploads/' . $social->social_image))) {
                unlink(public_path('admin_uploads/' . $social->social_image));
            }
            
            $image = $request->file('social_image');
            $name = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('admin_uploads'), $name);
            $data['social_image'] = $name;
        }

        $social->update($data);

        return redirect()->route('social.index')->with('success', 'Social link updated successfully!');
    }

    public function toggleStatus($id)
    {
        $social = SocialLink::findOrFail($id);
        $social->status = $social->status == 'active' ? 'inactive' : 'active';
        $social->save();

        return back()->with('success', 'Status updated successfully!');
    }
}