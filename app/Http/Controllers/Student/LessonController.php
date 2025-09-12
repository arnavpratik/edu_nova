<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    
    public function show(Lesson $lesson)
    {
        
        $lessonsInCourse = $lesson->course->lessons()->orderBy('id', 'asc')->get();

        
        $currentIndex = $lessonsInCourse->search(function ($item) use ($lesson) {
            return $item->id === $lesson->id;
        });

        
        $previousLesson = $currentIndex > 0 ? $lessonsInCourse[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $lessonsInCourse->count() - 1 ? $lessonsInCourse[$currentIndex + 1] : null;

        return view('student.lessons.show', [
            'lesson' => $lesson,
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
        ]);
    }
}