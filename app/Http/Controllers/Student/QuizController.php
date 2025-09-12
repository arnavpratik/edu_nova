<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
   
    public function take(Quiz $quiz)
    {
        $quiz->load('questions.answers');
        return view('student.quizzes.take', ['quiz' => $quiz]);
    }


    public function submit(Request $request, Quiz $quiz)
    {
        
        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer|exists:answers,id',
        ]);

        $submittedAnswers = $validated['answers'];
        $questions = $quiz->questions()->with('answers')->get();
        $totalQuestions = $questions->count();
        $correctAnswersCount = 0;
        $resultsData = [];

        foreach ($questions as $question) {
            $correctAnswer = $question->answers->where('is_correct', true)->first();
            $submittedAnswerId = $submittedAnswers[$question->id] ?? null;

            $isCorrect = ($submittedAnswerId && $submittedAnswerId == $correctAnswer->id);
            if ($isCorrect) {
                $correctAnswersCount++;
            }

            $resultsData[] = [
                'question_text' => $question->question_text,
                'submitted_answer' => $submittedAnswerId ? \App\Models\Answer::find($submittedAnswerId)->answer_text : 'No answer',
                'correct_answer' => $correctAnswer->answer_text,
                'is_correct' => $isCorrect,
            ];
        }

        $score = ($totalQuestions > 0) ? round(($correctAnswersCount / $totalQuestions) * 100) : 0;
        
        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'results_data' => json_encode($resultsData)
        ]);

        
        return redirect()->route('student.quizzes.results', $attempt->id);
    }

    
    public function results(QuizAttempt $attempt)
{
    
    $results = json_decode($attempt->results_data);

    
    return view('student.quizzes.results', [
        'attempt' => $attempt,
        'results' => $results,
    ]);
}
}