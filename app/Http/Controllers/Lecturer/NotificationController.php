<?php

namespace App\Http\Controllers\Lecturer;

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
        $senderId = Auth::id();
        $sentNotifications = SentNotification::where('sender_id', $senderId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Only get classes taught by this instructor
        $classes = AcademicClass::where('lecturer_id', Auth::user()->lecturer->id)
            ->with('course')
            ->get();
        
        return view('lecturer.notification.index', compact('sentNotifications', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target_type' => 'required|string|in:subject',
            'target_id' => 'required|exists:classes,id',
        ]);

        $senderId = Auth::id();
        
        // Security check: Ensure lecturer teaches this class
        $class = AcademicClass::where('id', $request->target_id)
            ->where('lecturer_id', Auth::user()->lecturer->id)
            ->firstOrFail();

        $count = $this->notificationService->sendToClass($request->target_id, $request->title, $request->message, $senderId);

        return redirect()->back()->with('success', __('Successfully sent notification to :count students', ['count' => $count]));
    }
}
