<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicClass;
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

    public function store(Request $request, AcademicClass $class)
    {
        $student = Auth::user()->student;

        if (!$student) {
            return back()->with('error', __('You do not have access as a student.'));
        }

        // Check if a request already exists
        $existingRequest = ChatRequest::where('student_id', $student->id)
            ->where('academic_class_id', $class->id)
            ->first();

        if ($existingRequest) {
            if ($existingRequest->status === 'pending') {
                return back()->with('warning', __('You already have a pending chat request for this class.'));
            } elseif ($existingRequest->status === 'approved') {
                return back()->with('info', __('Your chat request has already been approved. You can start chatting.'));
            }
            $existingRequest->delete();
        }

        $chatRequest = ChatRequest::create([
            'student_id' => $student->id,
            'lecturer_id' => $class->lecturer_id,
            'academic_class_id' => $class->id,
            'status' => 'pending',
            'message' => $request->message ?? __('I would like to chat with you regarding :course', ['course' => $class->course->course_name]),
        ]);

        // Send notification to lecturer
        $this->notificationService->sendToUsers(
            [$class->lecturer->user_id],
            __('New Chat Request'),
            __(':student has requested a chat for class :class', [
                'student' => Auth::user()->name,
                'class' => $class->course->course_name
            ]),
            'chat_request',
            ['chat_request_id' => $chatRequest->id]
        );

        return back()->with('success', __('Chat request sent successfully.'));
    }
}
