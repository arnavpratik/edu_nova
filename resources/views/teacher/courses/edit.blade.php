<x-app-layout>
    <x-slot name="header">
        <!-- HEADER WITH NEW BACK BUTTON -->
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Course') }}
            </h2>
            <a href="{{ route('teacher.dashboard') }}" class="inline-block bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('teacher.courses.update', $course) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Course Title -->
                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700">Title</label>
                            <input id="title" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="title" value="{{ old('title', $course->title) }}" required autofocus />
                            @error('title')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Course Description -->
                        <div class="mt-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Revision Sheet Upload -->
                        <div class="mt-4">
                            <label for="revision_sheet" class="block font-medium text-sm text-gray-700">Revision Sheet (PDF only, 2MB Max)</label>
                            <input id="revision_sheet" class="block mt-1 w-full" type="file" name="revision_sheet" />
                            @error('revision_sheet')
                                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                            
                            @if ($course->revision_sheet_path)
                                <div class="mt-2 text-sm text-gray-600">
                                    Current file: <a href="{{ asset('storage/' . $course->revision_sheet_path) }}" target="_blank" class="text-blue-600 underline">View Sheet</a>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700">
                                Update Course
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>