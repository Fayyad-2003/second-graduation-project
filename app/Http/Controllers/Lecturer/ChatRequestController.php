<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\ChatRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatRequestController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $lecturer = Auth::user()->lecturer;

        if (!$lecturer) {
            abort(403, __('You do not have access as a lecturer.'));
        }

        $requests = ChatRequest::where('lecturer_id', $lecturer->id)
            ->with(['student.user', 'academicClass.course'])
            ->latest()
            ->get();

        return view('lecturer.chat-requests.index', compact('requests'));
    }

    public function update(Request $request, ChatRequest $chatRequest)
    {
        $lecturer = Auth::user()->lecturer;

        if (!$lecturer || $chatRequest->lecturer_id !== $lecturer->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $chatRequest->update([
            'status' => $request->status,
        ]);

        // Send notification to student
        $title = $request->status === 'approved' ? __('Chat Request Approved') : __('Chat Request Rejected');
        $message = $request->status === 'approved'
            ? __('Your chat request for :class has been approved by :lecturer.', [
                'class' => $chatRequest->academicClass->course->course_name,
                'lecturer' => Auth::user()->name
            ])
            : __('Your chat request for :class has been rejected.', [
                'class' => $chatRequest->academicClass->course->course_name
            ]);

        $this->notificationService->sendToUsers(
            [$chatRequest->student->user_id],
            $title,
            $message,
            'chat_request_update',
            ['chat_request_id' => $chatRequest->id, 'status' => $request->status]
        );

        $statusMessage = $request->status === 'approved' ? __('Chat request approved.') : __('Chat request rejected.');

        return back()->with('success', $statusMessage);
    }
}
