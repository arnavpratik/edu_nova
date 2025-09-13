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
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $receiver->id);
        })->orWhere(function ($query) use ($receiver) {
            $query->where('sender_id', $receiver->id)
                ->where('receiver_id', Auth::id());
        })->oldest()->get();

        return view('chat', [
            'receiver' => $receiver,
            'messages' => $messages
        ]);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();

            // ✅ Save to /public/chat-images/
            $imageFile->move(public_path('chat-images'), $imageName);

            // ✅ Store relative path in DB
            $imagePath = 'chat-images/' . $imageName;
        }

        if (!$request->filled('message') && !$imagePath) {
            return response()->json(['error' => 'Message cannot be empty.'], 422);
        }

        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message'     => $request->message,
            'image_path'  => $imagePath,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        // ✅ Always return absolute image URL so frontend can display instantly
        return response()->json([
            'status' => 'Message Sent!',
            'message' => [
                'id'         => $message->id,
                'message'    => $message->message,
                'image_url'  => $message->image_path ? asset($message->image_path) : null,
                'sender_id'  => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'created_at' => $message->created_at->toDateTimeString(),
            ]
        ]);
    }

    public function index()
    {
        $studentIds = Message::where('receiver_id', Auth::id())
            ->distinct()
            ->pluck('sender_id');

        $students = User::whereIn('id', $studentIds)->get();

        return view('teacher.chats', ['students' => $students]);
    }

    public function fetchMessages(User $receiver, Request $request)
    {
        $lastMessageId = $request->input('last_message_id', 0);

        $messages = Message::where(function ($query) use ($receiver) {
            $query->where('sender_id', $receiver->id)
                ->where('receiver_id', Auth::id());
        })
            ->where('id', '>', $lastMessageId)
            ->oldest()
            ->get();

        // Transform messages so frontend always gets image_url
        $messages->transform(function ($msg) {
            return [
                'id' => $msg->id,
                'sender_id' => $msg->sender_id,
                'receiver_id' => $msg->receiver_id,
                'message' => $msg->message,
                'image_url' => $msg->image_path ? asset($msg->image_path) : null,
                'created_at' => $msg->created_at->toDateTimeString(),
            ];
        });

        return response()->json($messages);
    }
}
