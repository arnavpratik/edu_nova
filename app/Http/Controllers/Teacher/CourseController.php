<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    
    public function create()
    {
        return view('teacher.courses.create');
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'revision_sheet' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $path = null;
        
        if ($request->hasFile('revision_sheet')) {
            
            $path = $request->file('revision_sheet')->store('revision_sheets', 'public');
        }

        
        $course = new Course([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'teacher_id' => Auth::id(),
            'revision_sheet_path' => $path, 
        ]);
        $course->save();

        return redirect()->route('teacher.dashboard')->with('success', 'Course created successfully!');
    }

    
    public function show(Course $course)
    {
        return view('teacher.courses.show', ['course' => $course]);
    }

   
    public function edit(Course $course)
    {
        return view('teacher.courses.edit', ['course' => $course]);
    }

   
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'revision_sheet' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $path = $course->revision_sheet_path;
        if ($request->hasFile('revision_sheet')) {
            
            if ($path) {
                Storage::disk('public')->delete($path);
            }
            
            $path = $request->file('revision_sheet')->store('revision_sheets', 'public');
        }

        
        $course->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'revision_sheet_path' => $path,
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Course updated successfully!');
    }

    
    public function destroy(Course $course)
    {
        
    }
}