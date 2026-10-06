<?php

namespace App\Http\Controllers;

use App\Models\OtherDocument;
use Illuminate\Http\Request;

class OtherDocumentController extends Controller
{
    public function index() {
        $documents = OtherDocument::all();
        return view('settings.other_documents', compact('documents'))->with(['editMode' => false, 'editData' => null]);
    }

    public function store(Request $request) {
        $request->validate([
            'document_name' => 'required|string',
            'file.*' => 'required|file|mimes:jpeg,png,jpg,pdf',
        ]);

        foreach ($request->file('file') as $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/documents'), $fileName);

            OtherDocument::create([
                'document_name' => $request->document_name,
                'type' => $file->getClientOriginalExtension(),
                'file' => $fileName,
                'status' => 'active',
            ]);
        }

        return back()->with('success', 'Documents uploaded successfully!');
    }

    public function edit($id) {
        $editData = OtherDocument::findOrFail($id);
        $documents = OtherDocument::all();
        return view('settings.other_documents', compact('editData', 'documents'))->with('editMode', true);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'document_name' => 'required|string',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,pdf',
        ]);

        $doc = OtherDocument::findOrFail($id);
        $doc->document_name = $request->document_name;

        if ($request->hasFile('file')) {
            // delete old file
            if ($doc->file && file_exists(public_path('uploads/documents/' . $doc->file))) {
                unlink(public_path('uploads/documents/' . $doc->file));
            }
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/documents'), $fileName);
            $doc->file = $fileName;
            $doc->type = $file->getClientOriginalExtension();
        }

        $doc->save();
        return redirect()->route('document.index')->with('success', 'Document updated successfully!');
    }

    public function toggleStatus($id) {
        $doc = OtherDocument::findOrFail($id);
        $doc->status = $doc->status == 'active' ? 'inactive' : 'active';
        $doc->save();
        return back()->with('success', 'Status updated!');
    }
}