<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class LessonController extends Controller
{
    public function create(Request $request)
    {
        $course_id = $request->query('course');
        return view('teacher.lessons.create', ['course_id' => $course_id]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        Lesson::create($validated);

        return redirect()->route('teacher.courses.show', $validated['course_id'])
               ->with('success', 'New lesson added successfully!');
    }

    public function edit(Lesson $lesson)
    {
        $lesson->load('quiz.questions.answers');
        return view('teacher.lessons.edit', ['lesson' => $lesson]);
    }

    
    public function update(Request $request, Lesson $lesson)
    {
        
        $validatedLesson = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $lesson->update($validatedLesson);

        
        if ($request->filled('new_question_text')) {
            
            $quiz = $lesson->quiz()->firstOrCreate(['title' => $lesson->title . ' Quiz']);

            
            $validatedQuestion = $request->validate([
                'new_question_text' => 'required|string',
                'new_answers' => 'required|array|min:4',
                'new_answers.*' => 'required|string',
                'new_is_correct' => 'required|integer|between:0,3',
            ]);

            
            $question = $quiz->questions()->create([
                'question_text' => $validatedQuestion['new_question_text'],
            ]);

           
            foreach ($validatedQuestion['new_answers'] as $index => $answerText) {
                $question->answers()->create([
                    'answer_text' => $answerText,
                    'is_correct' => ($index == $validatedQuestion['new_is_correct']),
                ]);
            }
        }

        
        return redirect()->route('teacher.lessons.edit', $lesson)
               ->with('success', 'Lesson and quiz updated successfully!');
    }

    public function destroy(Lesson $lesson)
    {
        $courseId = $lesson->course_id;
        $lesson->delete();
        return redirect()->route('teacher.courses.show', $courseId)
               ->with('success', 'Lesson deleted successfully!');
    }
}