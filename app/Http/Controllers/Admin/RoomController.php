<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('faculty');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('room_name', 'like', "%{$search}%")
                    ->orWhere('room_code', 'like', "%{$search}%")
                    ->orWhere('building', 'like', "%{$search}%");
            });
        }

        // Faculty scoping for admin_faculty
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $facultyId = $request->get('faculty_scope');
            $query->where(function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId)
                    ->orWhereNull('faculty_id'); // Also show unassigned ones
            });
        }

        // Sorting
        $sortColumn = $request->get('sort', 'room_code');
        $sortDirection = $request->get('order', 'asc');

        // Map Indonesian column names to English
        $columnMap = [
            'room_code' => 'room_code',
            'room_name' => 'room_name',
            'capacity' => 'capacity',
            'building' => 'building',
            'floor' => 'floor',
        ];

        $dbColumn = $columnMap[$sortColumn] ?? $sortColumn;
        $allowedSorts = ['room_code', 'room_name', 'capacity', 'building', 'floor', 'is_active'];

        if (in_array($dbColumn, $allowedSorts)) {
            $query->orderBy($dbColumn, $sortDirection);
        } else {
            $query->orderBy('room_code', 'asc');
        }

        $roomList = $query->paginate(config('system.pagination', 15))->withQueryString();

        // Stats - also scoped
        $statsQuery = Room::query();
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $facultyId = $request->get('faculty_scope');
            $statsQuery->where(function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId)
                    ->orWhereNull('faculty_id');
            });
        }
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'active' => (clone $statsQuery)->where('is_active', true)->count(),
            'capacity' => (clone $statsQuery)->sum('capacity'),
            'building_count' => (clone $statsQuery)->distinct('building')->count('building'),
        ];

        // Faculty list for dropdown (only for superadmin)
        $facultyList = auth()->user()->isSuperAdmin() ? Faculty::all() : collect();

        return view('admin.room.index', compact('roomList', 'stats', 'facultyList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_code' => 'required|string|max:20|unique:rooms,room_code',
            'room_name' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'building' => 'nullable|string|max:50',
            'floor' => 'nullable|integer|min:1',
            'facilities' => 'nullable|string',
            'is_active' => 'boolean',
            'faculty_id' => 'nullable|exists:faculties,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Auto-assign faculties for admin_faculty
        if (empty($validated['faculty_id']) && $request->get('faculty_scoped')) {
            $validated['faculty_id'] = $request->get('faculty_scope');
        }

        Room::create($validated);

        return redirect()->back()->with('success', __('Room successfully added'));
    }

    public function update(Request $request, Room $rooms)
    {
        $validated = $request->validate([
            'room_code' => 'required|string|max:20|unique:rooms,room_code,' . $rooms->id,
            'room_name' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'building' => 'nullable|string|max:50',
            'floor' => 'nullable|integer|min:1',
            'facilities' => 'nullable|string',
            'is_active' => 'boolean',
            'faculty_id' => 'nullable|exists:faculties,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $rooms->update($validated);

        return redirect()->back()->with('success', __('Room successfully updated'));
    }

    public function destroy(Room $rooms)
    {
        // Check if rooms is used in schedule
        if ($rooms->scheduleKuliah()->exists()) {
            return redirect()->back()->withErrors(['error' => __('Cannot delete rooms that are used in schedules.')]);
        }

        $rooms->delete();
        return redirect()->back()->with('success', __('Room successfully deleted'));
    }
}
