<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        
        $teachers = User::where('role', 'teacher')->get();

        if ($teachers->isEmpty()) {
            return; 
        }

        
        Course::factory()->count(40)->make()->each(function ($course) use ($teachers) {
            
            $course->teacher_id = $teachers->random()->id;
            $course->save();

            
            $lessons = Lesson::factory()->count(3)->create(['course_id' => $course->id]);

          
            if ($lessons->isNotEmpty()) {
                $quiz = Quiz::factory()->create(['lesson_id' => $lessons->first()->id]);

                
                Question::factory()->count(10)->create(['quiz_id' => $quiz->id])
                    ->each(function ($question) {
                        
                        Answer::factory()->count(4)->create(['question_id' => $question->id]);

                     
                        $question->answers()->inRandomOrder()->first()->update(['is_correct' => true]);
                    });
            }
        });
    }
}