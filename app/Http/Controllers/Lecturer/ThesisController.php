<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\ThesisSupervision;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThesisController extends Controller
{
    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        if (!$lecturer) {
            abort(403);
        }

        // Get all theses where this lecturer is supervisor
        $thesisList = Thesis::where('supervisor1_id', $lecturer->id)
            ->orWhere('supervisor2_id', $lecturer->id)
            ->with(['student.user', 'supervisor1.user', 'supervisor2.user'])
            ->orderByRaw("FIELD(status, 'supervision', 'submission', 'review', 'proposal_seminar', 'research', 'result_seminar', 'defense', 'revision', 'completed', 'rejected', 'accepted')")
            ->get();

        // Pending supervision
        $pendingSupervision = ThesisSupervision::where('lecturer_id', $lecturer->id)
            ->where('status', ThesisSupervision::STATUS_WAITING)
            ->with('thesis.student.user')
            ->count();

        return view('lecturer.thesis.index', compact('lecturer', 'thesisList', 'pendingSupervision'));
    }

    public function show(Thesis $thesis)
    {
        $lecturer = Auth::user()->lecturer;

        // Verify lecturer is supervisor
        if ($thesis->supervisor1_id !== $lecturer->id && $thesis->supervisor2_id !== $lecturer->id) {
            abort(403, __('You are not a supervisor for this thesis'));
        }

        $thesis->load(['student.user', 'supervisor1.user', 'supervisor2.user', 'supervision.lecturer.user']);

        return view('lecturer.thesis.show', compact('lecturer', 'thesis'));
    }

    public function reviewSupervision(Request $request, ThesisSupervision $supervision)
    {
        $lecturer = Auth::user()->lecturer;

        if ($supervision->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'lecturer_notes' => 'required|string',
            'status' => 'required|in:approved,revision',
        ]);

        $supervision->update([
            'lecturer_notes' => $validated['lecturer_notes'],
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', __('Supervision review saved successfully'));
    }

    public function updateStatus(Request $request, Thesis $thesis)
    {
        $lecturer = Auth::user()->lecturer;

        if ($thesis->supervisor1_id !== $lecturer->id && $thesis->supervisor2_id !== $lecturer->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Thesis::getStatusList())),
        ]);

        $thesis->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', __('Thesis status updated successfully'));
    }
}
