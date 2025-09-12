<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Course Details') }}
            </h2>

            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                &laquo; Back to Courses
            </a>
        </div>
    </x-slot>

    <div class="py-10 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
        <div class="bg-white shadow rounded-lg p-6 mb-10">
            <h1 class="text-3xl font-bold mb-4 text-gray-900">{{ $course->title }}</h1>
            <p class="text-gray-700 text-base sm:text-lg mb-3">{{ $course->description }}</p>
            <p class="text-sm text-gray-500">Created on: {{ $course->created_at->format('M d, Y') }}</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-semibold text-gray-800">Lessons</h3>
                <a href="{{ route('teacher.lessons.create', ['course' => $course->id]) }}"
                   class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                    Add New Lesson
                </a>
            </div>

            @if($course->lessons->isEmpty())
                <p class="text-gray-600 italic">No lessons created yet.</p>
            @else
                <ul class="space-y-4">
                    @foreach ($course->lessons as $lesson)
                    <li class="flex flex-col md:flex-row md:items-center md:justify-between border rounded-md p-5 hover:shadow-lg transition-shadow duration-200 ease-in-out">
                        <div class="mb-4 md:mb-0 md:max-w-xl">
                            <h4 class="text-xl font-semibold text-gray-900">{{ $lesson->title }}</h4>
                            <p class="text-gray-600 mt-1">{{ Str::limit(strip_tags($lesson->content), 120) }}</p>
                        </div>

                        <div class="flex space-x-4 text-sm">
                            <a href="{{ route('teacher.lessons.edit', $lesson) }}"
                               class="px-4 py-2 text-blue-600 font-semibold border border-blue-600 rounded hover:bg-blue-600 hover:text-white transition">
                                Edit
                            </a>
                            <form action="{{ route('teacher.lessons.destroy', $lesson) }}"
                                  method="POST" onsubmit="return confirm('Are you sure you want to delete this lesson?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 text-red-600 font-semibold border border-red-600 rounded hover:bg-red-600 hover:text-white transition">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>