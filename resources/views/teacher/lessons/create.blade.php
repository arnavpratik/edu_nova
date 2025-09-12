<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add New Lesson
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded-lg mt-6">
        <form action="{{ route('teacher.lessons.store') }}" method="POST">
            @csrf

            <input type="hidden" name="course_id" value="{{ $course_id }}" />

            <div class="mb-6">
                <label for="title" class="block mb-2 font-medium text-gray-700">Lesson Title</label>
                <input type="text" name="title" id="title" required
                       class="w-full rounded-md border border-gray-300 p-3"
                       value="{{ old('title') }}" />
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="content" class="block mb-2 font-medium text-gray-700">Lesson Content</label>
                <textarea name="content" id="content" rows="10" required
                          class="w-full rounded-md border border-gray-300 p-3">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                        class="inline-block bg-blue-600 text-white font-semibold px-6 py-3 rounded-md hover:bg-blue-700 transition">
                    Save Lesson
                </button>
            </div>
        </form>
    </div>
</x-app-layout>