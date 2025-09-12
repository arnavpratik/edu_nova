<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    
    public function show(Course $course)
    {
        
        $user = Auth::user();
        $totalScore = 0;
        
        
        $allQuizzesInCourse = $course->lessons->map(function ($lesson) {
            return $lesson->quiz;
        })->filter(); 

        $quizCount = $allQuizzesInCourse->count();

       
        foreach ($allQuizzesInCourse as $quiz) {
            $latestAttempt = $user->quizAttempts()
                ->where('quiz_id', $quiz->id)
                ->latest()
                ->first();
            
            if ($latestAttempt) {
                $totalScore += $latestAttempt->score;
            }
          
        }
        
       
        $averageScore = ($quizCount > 0) ? ($totalScore / $quizCount) : 0;
        
       
        return view('student.courses.show', [
            'course' => $course,
            'averageScore' => $averageScore,
        ]);
    }
}