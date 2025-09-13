<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Storage;

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
        // 1. Validate and update lesson details
        $validatedLesson = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $lesson->update($validatedLesson);

        // 2. Handle new question/quiz creation if data is present
        if ($request->filled('new_question_type')) {

            // Create or find the quiz for this lesson
            $quiz = $lesson->quiz()->firstOrCreate(['title' => $lesson->title . ' Quiz']);

            // ✅ FIXED: All validation rules are now combined into a single call.
            // This makes the entire operation atomic.
            $validatedQuizData = $request->validate([
                'new_question_type'     => 'required|in:text,image',
                'new_question_text'     => 'required_if:new_question_type,text|nullable|string|max:1000',
                'new_question_image'    => 'required_if:new_question_type,image|image|mimes:jpeg,png,jpg|max:5120', // Increased to 5MB

                'new_answers'           => 'required|array|min:2|max:4',
                'new_answers.*.type'    => 'required|string|in:text,image',
                'new_answers.*.text'    => 'required_if:new_answers.*.type,text|nullable|string|max:255',
                'new_answers.*.image'   => 'required_if:new_answers.*.type,image|image|mimes:jpeg,png,jpg|max:5120', // Increased to 5MB
                'new_is_correct'        => 'required|integer',
            ]);

            // --- Question Creation ---
            $questionContent = '';
            if ($validatedQuizData['new_question_type'] === 'image') {
                // Use the validated file from the request
                $path = $request->file('new_question_image')->store('question_images', 'public');
                $questionContent = $path;
            } else {
                $questionContent = $validatedQuizData['new_question_text'];
            }

            $question = $quiz->questions()->create([
                'type'    => $validatedQuizData['new_question_type'],
                'content' => $questionContent,
            ]);

            // --- Answer Creation ---
            foreach ($validatedQuizData['new_answers'] as $index => $answerData) {
                $answerContent = '';
                $answerType = $answerData['type'];

                if ($answerType === 'image') {
                    if ($request->hasFile("new_answers.{$index}.image")) {
                        $path = $request->file("new_answers.{$index}.image")->store('answer_images', 'public');
                        $answerContent = $path;
                    }
                } else {
                    $answerContent = $answerData['text'];
                }

                if (isset($answerContent)) { // Check if content is set (even empty string)
                    $question->answers()->create([
                        'type'       => $answerType,
                        'content'    => $answerContent,
                        'is_correct' => ($index == $validatedQuizData['new_is_correct']),
                    ]);
                }
            }
        }

        // 3. Redirect back with success message
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
