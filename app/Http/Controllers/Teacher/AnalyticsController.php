<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\EngagementLog;
use App\Models\User; // Make sure User model is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function show(Course $course)
    {
        
        $lessonIds = $course->lessons()->pluck('id');
        $engagementData = EngagementLog::whereIn('lesson_id', $lessonIds)
            ->with('lesson')
            ->select(
                'lesson_id',
                DB::raw('SUM(active_seconds) as total_active_seconds'),
                DB::raw('SUM(idle_seconds) as total_idle_seconds'),
                DB::raw('SUM(tab_switches) as total_tab_switches')
            )
            ->groupBy('lesson_id')
            ->get();

        
        $students = $course->students()->get();
        $quizIds = $course->lessons->pluck('quiz.id')->filter();
        
        $studentPerformanceData = [];
        foreach ($students as $student) {
            $averageScore = $student->quizAttempts()
                ->whereIn('quiz_id', $quizIds)
                ->avg('score'); 

            $totalActiveTime = $student->engagementLogs()
                ->whereIn('lesson_id', $lessonIds)
                ->sum('active_seconds');

            $studentPerformanceData[] = [
                'name' => $student->name,
                
                'average_score' => is_null($averageScore) ? null : round($averageScore),
                'total_engagement_minutes' => round($totalActiveTime / 60, 1),
            ];
        }

        return view('teacher.analytics.show', [
            'course' => $course,
            'engagementData' => $engagementData,
            'studentPerformanceData' => $studentPerformanceData,
        ]);
    }
}