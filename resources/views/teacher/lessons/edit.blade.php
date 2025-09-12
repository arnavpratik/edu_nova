<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Lesson & Quiz
            </h2>
            <a href="{{ route('teacher.courses.show', $lesson->course) }}" class="inline-block bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                &larr; Back to Course Details
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('teacher.lessons.update', $lesson) }}">
                @csrf
                @method('PATCH')

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-semibold mb-4">Lesson Details</h3>
                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700">Lesson Title</label>
                            <input id="title" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" type="text" name="title" value="{{ old('title', $lesson->title) }}" required />
                        </div>
                        <div class="mt-4">
                            <label for="content" class="block font-medium text-sm text-gray-700">Lesson Content</label>
                            <textarea id="content" name="content" rows="10" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>{{ old('content', $lesson->content) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-semibold mb-4">Quiz Management</h3>

                        <h4 class="text-lg font-semibold mb-2">Existing Questions</h4>
                        <div class="space-y-4 mb-6">
                            @if ($lesson->quiz && $lesson->quiz->questions->isNotEmpty())
                                @foreach ($lesson->quiz->questions as $question)
                                    <div class="p-3 border rounded-md bg-gray-50">
                                        <p class="font-medium">{{ $question->question_text }}</p>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500 italic">No questions have been added to this quiz yet.</p>
                            @endif
                        </div>

                        <div class="border-t pt-4">
                            <h4 class="text-lg font-semibold mb-2">Add a New Question</h4>
                            
                            <div class="mt-4">
                                <label for="new_question_text" class="block font-medium text-sm text-gray-700">Question Text</label>
                                <textarea name="new_question_text" rows="3" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" placeholder="e.g., What does HTML stand for?"></textarea>
                            </div>
                            
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                @for ($i = 0; $i < 4; $i++)
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Answer {{ $i + 1 }}</label>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <input type="radio" name="new_is_correct" value="{{ $i }}" class="form-radio text-blue-600">
                                            <input type="text" name="new_answers[]" class="block w-full rounded-md shadow-sm border-gray-300">
                                        </div>
                                    </div>
                                @endfor
                            </div>
                            <p class="text-xs text-gray-500 mt-2">To add a question, fill in the text and all four answers, then select the radio button next to the correct answer.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                        Save All Changes
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>