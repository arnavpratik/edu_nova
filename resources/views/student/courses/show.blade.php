<x-app-layout>
    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">
                <a href="{{ route('student.dashboard') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                    &laquo; Back to Dashboard
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h1 class="text-3xl font-bold text-blue-700 mb-3">{{ $course->title }}</h1>
                <p class="text-gray-700 text-lg">{{ $course->description }}</p>
            </div>

            {{-- First, check if a link has been added for this course --}}
            @if ($course->revision_sheet_path)
                {{-- Then, check if the student's score is high enough --}}
                @if ($averageScore >= 75)
                    <div class="bg-green-100 border border-green-300 p-4 rounded-lg mb-6 text-center">
                        <h4 class="font-semibold text-green-800">Congratulations! You've unlocked the revision sheet for this course.</h4>
                        {{-- The href now points directly to the URL from the database --}}
                        <a href="{{ asset('storage/' . $course->revision_sheet_path) }}" 
                           target="_blank" 
                           class="inline-block mt-3 bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700 transition">
                            View Revision Sheet
                        </a>
                    </div>
                @endif
            @endif
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Lessons</h2>
                <ul class="space-y-3">
                    @forelse($course->lessons as $lesson)
                    <li>
                        <a href="{{ route('student.lessons.show', $lesson) }}"
                           class="block px-4 py-2 rounded-md bg-blue-50 text-blue-700 font-medium hover:bg-blue-100 transition">
                            {{ $lesson->title }}
                        </a>
                    </li>
                    @empty
                        <p class="text-gray-500 italic">No lessons have been added to this course yet.</p>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>