<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Faculty;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Check if user is superadmin, redirect if not
     */
    private function authorizeSuperAdmin(): void
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(response()->view('errors.403', ['message' => __('Only superadmin can manage users.')], 403));
        }
    }


    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();

        $query = User::with(['faculty', 'student.studyProgram', 'lecturer.studyProgram']);

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }

        // Faculty scoping for admin_faculty
        if ($request->get('faculty_scoped') && $request->get('faculty_scope')) {
            $facultyId = $request->get('faculty_scope');
            $query->where(function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId)
                    ->orWhereHas('student', fn($m) => $m->whereHas('studyProgram', fn($p) => $p->where('faculty_id', $facultyId)))
                    ->orWhereHas('lecturer', fn($d) => $d->whereHas('studyProgram', fn($p) => $p->where('faculty_id', $facultyId)));
            });
        }

        $users = $query->orderBy('name')->paginate(config('system.pagination', 15))->withQueryString();
        $faculties = Faculty::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'faculties'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', Password::min(8)],
            'role'     => 'required|in:superadmin,admin_faculty,lecturer,student',
            'faculty_id' => 'nullable|exists:faculties,id',
            'student_number'   => 'required_if:role,student|nullable|unique:students,student_number',
            'lecturer_number'  => 'required_if:role,lecturer|nullable|unique:lecturers,lecturer_number',
            'study_program_id' => 'required_if:role,student,lecturer|nullable|exists:study_programs,id',
            'batch'            => 'required_if:role,student|nullable|numeric',
        ]);

        // Compatibility mapping for legacy field names
        if (!isset($validated['faculty_id']) && $request->has('faculty_id')) $validated['faculty_id'] = $request->faculty_id;
        if (!isset($validated['student_number']) && $request->has('student_number')) $validated['student_number'] = $request->student_number;
        if (!isset($validated['lecturer_number']) && $request->has('lecturer_number')) $validated['lecturer_number'] = $request->lecturer_number;
        if (!isset($validated['study_program_id']) && $request->has('study_program_id')) $validated['study_program_id'] = $request->study_program_id;
        if (!isset($validated['batch']) && $request->has('batch')) $validated['batch'] = $request->batch;

        try {
            $user = $this->userService->createUser($validated);

            if ($request->expectsJson()) {
                return response()->json(['message' => __('User created'), 'data' => $user], 201);
            }

            return back()->with('success', __('User successfully added.'));
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'string', Password::min(8)],
            'role'     => 'required|in:superadmin,admin_faculty,lecturer,student',
            'faculty_id' => 'nullable|exists:faculties,id',
        ]);

        // Compatibility mapping
        if (!isset($validated['faculty_id']) && $request->has('faculty_id')) $validated['faculty_id'] = $request->faculty_id;

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'faculty_id' => $validated['faculty_id'] ?? null,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return back()->with('success', __('User successfully updated.'));
    }

    public function destroy(User $user)
    {
        $this->authorizeSuperAdmin();

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => __('Cannot delete your own account.')]);
        }

        // Prevent deletion of last superadmin
        if ($user->role === 'superadmin') {
            $superadminCount = User::where('role', 'superadmin')->count();
            if ($superadminCount <= 1) {
                return back()->withErrors(['error' => __('Cannot delete the last superadmin.')]);
            }
        }

        // Delete related records
        if ($user->student) {
            $user->student->delete();
        }
        if ($user->lecturer) {
            $user->lecturer->delete();
        }

        $user->delete();

        return back()->with('success', __('User successfully deleted.'));
    }
}
