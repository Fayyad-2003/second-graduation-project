<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Internship;
use App\Models\InternshipLogbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternshipController extends Controller
{
    public function index()
    {
        $lecturer = Auth::user()->lecturer;
        $internshipList = Internship::where('supervisor_id', $lecturer->id)
            ->with(['student.user'])
            ->orderByRaw("FIELD(status, 'ongoing', 'submission', 'approved', 'internship_completed', 'report_drafting', 'seminar', 'revision', 'completed', 'rejected')")
            ->get();

        $pendingLogbook = InternshipLogbook::whereHas('internship', fn($q) => $q->where('supervisor_id', $lecturer->id))
            ->where('status', InternshipLogbook::STATUS_PENDING)
            ->count();

        return view('lecturer.internship.index', compact('lecturer', 'internshipList', 'pendingLogbook'));
    }

    public function show(Internship $internship)
    {
        $lecturer = Auth::user()->lecturer;
        if ($internship->supervisor_id !== $lecturer->id) abort(403);

        $internship->load(['student.user', 'supervisor.user', 'logbook']);

        return view('lecturer.internship.show', compact('lecturer', 'internship'));
    }

    public function reviewLogbook(Request $request, InternshipLogbook $logbook)
    {
        $lecturer = Auth::user()->lecturer;
        if ($logbook->internship->supervisor_id !== $lecturer->id) abort(403);

        $validated = $request->validate([
            'supervisor_notes' => 'nullable|string',
            'status' => 'required|in:approved,revision',
        ]);

        $logbook->update($validated);

        return redirect()->back()->with('success', __('Logbook successfully reviewed'));
    }

    public function updateStatus(Request $request, Internship $internship)
    {
        $lecturer = Auth::user()->lecturer;
        if ($internship->supervisor_id !== $lecturer->id) abort(403);

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Internship::getStatusList())),
        ]);

        $internship->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', __('Status successfully updated'));
    }
}
