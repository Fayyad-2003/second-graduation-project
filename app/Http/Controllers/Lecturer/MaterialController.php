<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\Material;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    /**
     * Show materials for a specific classes
     */
    public function index($classId)
    {
        $lecturer = Auth::user()->lecturer;
        if (!$lecturer) {
            abort(403, __('You do not have access as a lecturer.'));
        }

        $class = $lecturer->classes()
            ->with(['course', 'schedules.meetings.materials'])
            ->findOrFail($classId);

        // Get all meetings for this class
        $meetingList = Meeting::whereHas('courseSchedule', fn($q) => $q->where('class_id', $classId))
            ->with('materials')
            ->orderBy('meeting_number')
            ->get();

        return view('lecturer.material.index', compact('class', 'meetingList'));
    }

    /**
     * Store new materials
     */
    public function store(Request $request, $classId)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        $validated = $request->validate([
            'meeting_id' => 'required|exists:meetings,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:20480', // 20MB max
            'link_external' => 'nullable|url|max:500',
        ]);

        // Verify meeting belongs to this class
        $meeting = Meeting::where('id', $validated['meeting_id'])
            ->whereHas('courseSchedule', fn($q) => $q->where('class_id', $classId))
            ->firstOrFail();

        $material = new Material([
            'class_id' => $classId,
            'meeting_id' => $meeting->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'link_external' => $validated['link_external'] ?? null,
            'order' => $meeting->materials()->count() + 1,
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs("materials/class_{$classId}", $filename);

            $material->file_path = $path;
            $material->file_name = $file->getClientOriginalName();
            $material->file_size = $file->getSize();
            $material->file_type = $file->getMimeType();
        }

        $material->save();

        return back()->with('success', __('Material successfully added.'));
    }

    /**
     * Update materials
     */
    public function update(Request $request, $classId, Material $material)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        // Verify material belongs to this class
        $meeting = $material->meeting;
        if ($meeting->courseSchedule->class_id != $classId) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:20480',
            'link_external' => 'nullable|url|max:500',
        ]);

        $material->title = $validated['title'];
        $material->description = $validated['description'] ?? null;
        $material->link_external = $validated['link_external'] ?? null;

        // Handle new file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($material->file_path) {
                Storage::delete($material->file_path);
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs("materials/class_{$classId}", $filename);

            $material->file_path = $path;
            $material->file_name = $file->getClientOriginalName();
            $material->file_size = $file->getSize();
            $material->file_type = $file->getMimeType();
        }

        $material->save();

        return back()->with('success', __('Material successfully updated.'));
    }

    /**
     * Delete materials
     */
    public function destroy($classId, Material $material)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        // Verify material belongs to this class
        $meeting = $material->meeting;
        if ($meeting->courseSchedule->class_id != $classId) {
            abort(403);
        }

        // Delete file if exists
        if ($material->file_path) {
            Storage::delete($material->file_path);
        }

        $material->delete();

        return back()->with('success', __('Material successfully deleted.'));
    }

    /**
     * Download materials file
     */
    public function download($classId, Material $material)
    {
        $lecturer = Auth::user()->lecturer;
        $class = $lecturer->classes()->findOrFail($classId);

        // Verify material belongs to this class
        $meeting = $material->meeting;
        if ($meeting->courseSchedule->class_id != $classId) {
            abort(403);
        }

        if (!$material->file_path || !Storage::exists($material->file_path)) {
            abort(404, __('File not found'));
        }

        return Storage::download($material->file_path, $material->file_name);
    }
}
