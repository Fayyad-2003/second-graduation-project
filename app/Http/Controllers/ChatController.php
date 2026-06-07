<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function show(ChatRequest $chatRequest)
    {
        // Ensure the user is either the student or the lecturer of the request
        $user = Auth::user();
        $isStudent = $user->student && $chatRequest->student_id === $user->student->id;
        $isLecturer = $user->lecturer && $chatRequest->lecturer_id === $user->lecturer->id;

        if (!$isStudent && !$isLecturer) {
            abort(403);
        }

        if ($chatRequest->status !== 'approved') {
            return back()->with('error', __('This chat is not approved yet.'));
        }

        $messages = $chatRequest->messages()->with('sender')->oldest()->get();

        // Mark messages as read
        $chatRequest->messages()->where('sender_id', '!=', $user->id)->update(['is_read' => true]);

        return view('chat.show', compact('chatRequest', 'messages'));
    }

    public function sendMessage(Request $request, ChatRequest $chatRequest)
    {
        $user = Auth::user();
        $isStudent = $user->student && $chatRequest->student_id === $user->student->id;
        $isLecturer = $user->lecturer && $chatRequest->lecturer_id === $user->lecturer->id;

        if (!$isStudent && !$isLecturer) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        ChatMessage::create([
            'chat_request_id' => $chatRequest->id,
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        return back()->with('success', __('Message sent.'));
    }
}
