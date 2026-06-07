<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentApplication;
use Illuminate\Http\Request;

class DocumentApplicationController extends Controller
{
    public function index()
    {
        $applications = DocumentApplication::with(['student.user', 'documentType'])
            ->latest()
            ->paginate(10);
        return view('admin.document-application.index', compact('applications'));
    }

    public function show(DocumentApplication $application)
    {
        $application->load(['student.user', 'documentType']);
        return view('admin.document-application.show', compact('application'));
    }

    public function updateStatus(Request $request, DocumentApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:processing,completed,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $updateData = [
            'status' => $validated['status'],
            'admin_notes' => $validated['admin_notes'],
        ];

        if ($validated['status'] === 'completed') {
            $updateData['completed_at'] = now();
        }

        $application->update($updateData);

        return redirect()->route('admin.document-application.index')->with('success', __('Application status updated successfully'));
    }
}
