<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Show the quiz taking interface.
     * This method is already correct and requires no changes.
     */
    public function take(Quiz $quiz)
    {
        $quiz->load('questions.answers');
        return view('student.quizzes.take', ['quiz' => $quiz]);
    }

    /**
     * ✅ MODIFIED: Submit the quiz, calculate score, and save results.
     */
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

        // Calculate the number of correct answers
        foreach ($questions as $question) {
            $correctAnswerId = $question->answers->where('is_correct', true)->first()->id;
            $submittedAnswerId = $submittedAnswers[$question->id] ?? null;

            if ($submittedAnswerId && $submittedAnswerId == $correctAnswerId) {
                $correctAnswersCount++;
            }
        }

        // Calculate the final score
        $score = ($totalQuestions > 0) ? round(($correctAnswersCount / $totalQuestions) * 100) : 0;
        
        // Create the quiz attempt record.
        // We now store a simple JSON map of { question_id: answer_id }.
        // This is much cleaner and more reliable than storing raw text.
        $attempt = QuizAttempt::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'results_data' => json_encode($submittedAnswers) // Save the user's answers
        ]);
        
        return redirect()->route('student.quizzes.results', $attempt->id);
    }

    /**
     * ✅ MODIFIED: Show the quiz results page.
     */
    public function results(QuizAttempt $attempt)
    {
        // Eager load the entire quiz structure. This gives the view access to all
        // the original questions and answers (including their types and content).
        $attempt->load('quiz.questions.answers');

        // Decode the simple JSON from the database to get the user's answers.
        // The 'true' argument makes it an associative array.
        $userAnswers = json_decode($attempt->results_data, true);
    
        // Pass the full attempt object AND the user's answers to the view.
        return view('student.quizzes.results', [
            'attempt' => $attempt,
            'userAnswers' => $userAnswers,
        ]);
    }
}