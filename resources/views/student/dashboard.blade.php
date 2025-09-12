@php
    // This line imports the Str class so we can use Str::limit() below
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($revisionLessons->isNotEmpty())
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-lg mb-8" role="alert">
                    <p class="font-bold text-lg">Your Personalized Revision Plan</p>
                    <p class="mb-2">Based on your recent quiz results, we suggest you review the following topics:</p>
                    
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($revisionLessons as $lesson)
                            <li>
                                <a href="{{ route('student.lessons.show', $lesson) }}" class="underline font-medium hover:text-yellow-900">
                                    {{ $lesson->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">My Courses</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($enrolledCourses as $course)
                            <div class="bg-gray-50 p-4 rounded-lg shadow flex flex-col">
                                <h4 class="font-bold text-xl">{{ $course->title }}</h4>
                                <p class="text-gray-600 my-2 flex-grow">{{ Str::limit($course->description, 70) }}</p>
                                <a href="{{ route('student.courses.show', $course) }}" class="inline-block w-full text-center bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition mt-auto">
                                    Continue Learning
                                </a>
                            </div>
                        @empty
                            <p class="text-gray-500 italic col-span-full">You are not enrolled in any courses yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">Available Courses</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($availableCourses as $course)
                            <div class="bg-gray-50 p-4 rounded-lg shadow flex flex-col">
                                <h4 class="font-bold text-xl">{{ $course->title }}</h4>
                                <p class="text-gray-600 my-2 flex-grow">{{ Str::limit($course->description, 70) }}</p>
                                <form method="POST" action="{{ route('student.courses.enroll', $course) }}" class="mt-auto">
                                    @csrf
                                    <button type="submit" class="inline-block w-full text-center bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
                                        Enroll Now
                                    </button>
                                </form>
                            </div>
                        @empty
                             <p class="text-gray-500 italic col-span-full">No new courses available to enroll in.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>