<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\User;


//Laravel's built-in HTTP Client
use Illuminate\Support\Facades\Http;

class CommentController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'body' => 'required|string|max:1000',
    ]);

    $user = auth()->user();

    if ($user->is_blocked_from_discussion) {
        return back()->with('error', 'You are blocked from posting comments.');
    }

    try {
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

        // PROMPT: Give the AI a specific job.
        $prompt = "You are a comment moderator for a college's website. Classify the following comment as either 'SAFE' or 'TOXIC'. Respond with only a single word. The comment is: '{$request->body}'";

        $response = Http::post($url, [
            'contents' => [
                [
                    'parts' => [
                        // Send the engineered prompt, not the raw comment
                        ['text' => $prompt]
                    ]
                ]
            ],
            
        ]);
        
        // First, check if the response is valid at all
        if ($response->failed() || !isset($response->json()['candidates'][0]['content']['parts'][0]['text'])) {
             \Log::error('Gemini API Error or Malformed Response:', $response->json());
             return back()->with('error', 'Error processing comment. Please try again.');
        }

        // THE NEW CHECK: Check the AI's single-word answer.
        $classification = trim($response->json()['candidates'][0]['content']['parts'][0]['text']);
        
        \Log::info('Gemini Classification:', ['result' => $classification]);

        if ($classification === 'TOXIC') {
            $user->is_blocked_from_discussion = true;
            $user->save();
            return back()->with('error', 'Your comment was deemed inappropriate. You are now blocked from further discussion.');
        }

        // If the classification was 'SAFE', save the comment
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->body = $request->body; // Save the original comment
        $comment->lesson_id = $request->route('lesson');
        $comment->save();

        return back()->with('success', 'Comment posted successfully!');

    } catch (\Exception $e) {
        \Log::error($e);
        return back()->with('error', 'Could not post comment at this time. Please try again later.');
    }
}
}
