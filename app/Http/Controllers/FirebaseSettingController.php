<?php
// app/Http/Controllers/FirebaseSettingController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FirebaseSetting;
use Illuminate\Support\Facades\Storage;

class FirebaseSettingController extends Controller
{
    public function index()
    {
        $firebase = FirebaseSetting::first();
        return view('settings.firebase', compact('firebase'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'nullable|string|max:255',
            'project_id' => 'nullable|string|max:255',
            'project_number' => 'nullable|string|max:255',
            'app_id' => 'nullable|string|max:255',
            'package_name' => 'nullable|string|max:255',
            'sender_id' => 'nullable|string|max:255',
            'server_key' => 'nullable|string',
            'key_pair' => 'nullable|string',
            'json_file' => 'nullable|file|mimes:json,txt|max:2048', // Allow JSON file upload
            'status' => 'nullable|in:on,off,1,0',
        ]);

        $firebase = FirebaseSetting::first();
        
        $data = [
            'project_name' => $request->project_name,
            'project_id' => $request->project_id,
            'project_number' => $request->project_number,
            'app_id' => $request->app_id,
            'package_name' => $request->package_name,
            'sender_id' => $request->sender_id,
            'server_key' => $request->server_key,
            'key_pair' => $request->key_pair,
            'status' => $request->has('status') ? 1 : 0,
        ];

        // Handle JSON file upload
        if ($request->hasFile('json_file')) {
            $file = $request->file('json_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Store file in storage/app/public/firebase directory
            $path = $file->storeAs('public/firebase', $fileName);
            
            // Save the file content to database as well (optional)
            $data['json_file'] = file_get_contents($file->getRealPath());
            
            // Or save just the file path
            // $data['json_file_path'] = Storage::url($path);
        }

        if ($firebase) {
            $firebase->update($data);
            $message = 'Firebase settings updated successfully!';
        } else {
            FirebaseSetting::create($data);
            $message = 'Firebase settings created successfully!';
        }

        return back()->with('success', $message);
    }

    public function getConfig()
    {
        $firebase = FirebaseSetting::first();
        
        if (!$firebase || !$firebase->status) {
            return response()->json([
                'status' => false,
                'message' => 'Firebase is not configured or disabled'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'config' => $firebase->getConfig()
        ]);
    }

    public function downloadJson($id)
    {
        $firebase = FirebaseSetting::findOrFail($id);
        
        if (!$firebase->json_file) {
            return back()->with('error', 'No JSON file found');
        }

        $filename = 'google-services.json';
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response($firebase->json_file, 200, $headers);
    }
}