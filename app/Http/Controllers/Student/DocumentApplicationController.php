<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\DocumentApplication;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentApplicationController extends Controller
{
    public function index()
    {
        $students = Auth::user()->student;
        abort_if(!$students, 403, 'No student profile found for this account.');
        $applications = DocumentApplication::where('student_id', $students->id)
            ->with('documentType')
            ->latest()
            ->get();
        $availableDocumentTypes = DocumentType::where('is_active', true)->get();

        return view('student.document-application.index', compact('applications', 'availableDocumentTypes'));
    }

    public function store(Request $request)
    {
        $students = Auth::user()->student;
        abort_if(!$students, 403, 'No student profile found for this account.');
        $docType = DocumentType::findOrFail($request->document_type_id);

        $rules = [
            'document_type_id' => 'required|exists:document_types,id',
        ];

        foreach ($docType->required_files as $index => $file) {
            $rules["files.{$index}"] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                $fileName = $docType->required_files[$index]['name'];
                $path = $file->store("documents/{$students->student_number}", 'public');
                $uploadedFiles[] = [
                    'name' => $fileName,
                    'path' => $path
                ];
            }
        }

        DocumentApplication::create([
            'student_id' => $students->id,
            'document_type_id' => $docType->id,
            'status' => 'pending',
            'uploaded_files' => $uploadedFiles,
        ]);

        return redirect()->back()->with('success', __('Application submitted successfully'));
    }
}
