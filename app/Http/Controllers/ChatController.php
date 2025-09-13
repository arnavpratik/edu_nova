<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function showChat(User $receiver)
    {
        $messages = Message::where(function ($query) use ($receiver) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $receiver->id);
        })->orWhere(function ($query) use ($receiver) {
            $query->where('sender_id', $receiver->id)->where('receiver_id', Auth::id());
        })->oldest()->get();

        return view('chat', ['receiver' => $receiver, 'messages' => $messages]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['receiver_id' => 'required|exists:users,id', 'message' => 'required|string|max:1000']);
        $message = Message::create(['sender_id' => Auth::id(), 'receiver_id' => $request->receiver_id, 'message' => $request->message]);
        broadcast(new MessageSent($message))->toOthers();
        return response()->json(['status' => 'Message Sent!']);
    }
    public function index()
{
    // Get the unique IDs of all users who have sent a message to the current teacher
    $studentIds = Message::where('receiver_id', Auth::id())
        ->distinct()
        ->pluck('sender_id');

    // Get the User models for these students
    $students = User::whereIn('id', $studentIds)->get();

    return view('teacher.chats', ['students' => $students]);
}

// Add this new method inside your ChatController

public function fetchMessages(User $receiver, Request $request)
{
    // Get the timestamp of the last message the user has
    $lastMessageId = $request->input('last_message_id', 0);

    $messages = Message::where(function ($query) use ($receiver) {
        $query->where('sender_id', $receiver->id)
              ->where('receiver_id', Auth::id());
    })
    ->where('id', '>', $lastMessageId) // Only get messages newer than the last one
    ->oldest()
    ->get();

    return response()->json($messages);
}
}