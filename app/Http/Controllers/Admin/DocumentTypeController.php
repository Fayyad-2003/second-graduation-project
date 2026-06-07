<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::latest()->paginate(10);
        return view('admin.document-type.index', compact('documentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_names' => 'required|array|min:1',
            'file_names.*' => 'required|string|max:255',
        ]);

        $requiredFiles = [];
        foreach ($validated['file_names'] as $fileName) {
            $requiredFiles[] = ['name' => $fileName];
        }

        DocumentType::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'required_files' => $requiredFiles,
        ]);

        return redirect()->back()->with('success', __('Document type created successfully'));
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_names' => 'required|array|min:1',
            'file_names.*' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $requiredFiles = [];
        foreach ($validated['file_names'] as $fileName) {
            $requiredFiles[] = ['name' => $fileName];
        }

        $documentType->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'required_files' => $requiredFiles,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', __('Document type updated successfully'));
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();
        return redirect()->back()->with('success', __('Document type deleted successfully'));
    }
}
