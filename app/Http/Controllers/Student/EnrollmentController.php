<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function enroll(Course $course)
    {
        
        Auth::user()->courses()->syncWithoutDetaching($course->id);

        return redirect()->route('student.dashboard')
               ->with('success', 'You have successfully enrolled in "' . $course->title . '"!');
    }
}