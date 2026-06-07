<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
use App\Models\SentNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $sentNotifications = SentNotification::with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $classes = AcademicClass::with('course')->get();

        return view('admin.notification.index', compact('sentNotifications', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_type' => 'required|string|in:all_students,lecturers,subject,level',
            'target_id' => 'required_if:target_type,subject,level',
        ]);

        $senderId = Auth::id();
        $count = 0;

        switch ($request->target_type) {
            case 'all_students':
                $count = $this->notificationService->sendToAllStudents($request->title, $request->message, $senderId);
                break;
            case 'lecturers':
                $count = $this->notificationService->sendToLecturers($request->title, $request->message, $senderId);
                break;
            case 'subject':
                $count = $this->notificationService->sendToClass($request->target_id, $request->title, $request->message, $senderId);
                break;
            case 'level':
                $count = $this->notificationService->sendToLevel($request->target_id, $request->title, $request->message, $senderId);
                break;
        }

        return redirect()->back()->with('success', __('Successfully sent notification to :count users', ['count' => $count]));
    }
}
