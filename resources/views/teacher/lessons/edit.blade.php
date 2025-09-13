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
            <form method="POST" action="{{ route('teacher.lessons.update', $lesson) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- Lesson Details Section --}}
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

                {{-- Quiz Management Section --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-2xl font-semibold mb-4">Quiz Management</h3>

                        {{-- MODIFIED: To correctly display existing text/image questions and answers --}}
                        <h4 class="text-lg font-semibold mb-2">Existing Questions</h4>
                        <div class="space-y-4 mb-6">
                            @if ($lesson->quiz && $lesson->quiz->questions->isNotEmpty())
                                @foreach ($lesson->quiz->questions as $question)
                                    <div class="p-4 border rounded-md bg-gray-50">
                                        {{-- Display Question (Text or Image) --}}
                                        @if ($question->type === 'text')
                                            <p class="font-bold text-lg">{{ $question->content }}</p>
                                        @else
                                            <img src="{{ Storage::url($question->content) }}" alt="Question Image" class="max-w-xs rounded-md shadow">
                                        @endif

                                        {{-- Display Answers with Correct Highlight --}}
                                        <div class="mt-3 space-y-2 pl-4">
                                            @foreach($question->answers as $answer)
                                                <div class="flex items-center text-sm @if($answer->is_correct) text-green-700 font-bold @endif">
                                                    <span>{{ $answer->is_correct ? '✔' : '●' }}</span>
                                                    @if($answer->type === 'text')
                                                        <p class="ml-2">{{ $answer->content }}</p>
                                                    @else
                                                        <img src="{{ Storage::url($answer->content) }}" alt="Answer Image" class="ml-2 h-16 w-auto rounded">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500 italic">No questions have been added to this quiz yet.</p>
                            @endif
                        </div>

                        {{-- MODIFIED: "Add New Question" form is now directly embedded here --}}
                        <div class="border-t pt-4">
                            <h4 class="text-lg font-semibold mb-2">Add a New Question</h4>
                            
                            <div class="new-question-section">
                                <div class="mt-4">
                                    <label for="questionTypeSelector" class="block font-medium text-sm text-gray-700">Question Type</label>
                                    <select name="new_question_type" id="questionTypeSelector" class="block mt-1 w-full rounded-md shadow-sm">
                                        <option value="text" selected>Text</option>
                                        <option value="image">Image</option>
                                    </select>
                                </div>
                        
                                <div id="text-input-container" class="mt-4">
                                    <label for="new_question_text" class="block font-medium text-sm text-gray-700">Question Text</label>
                                    <textarea name="new_question_text" class="block mt-1 w-full rounded-md shadow-sm"></textarea>
                                </div>
                        
                                <div id="image-input-container" class="mt-4" style="display: none;">
                                    <label for="new_question_image" class="block font-medium text-sm text-gray-700">Question Image</label>
                                    <input type="file" name="new_question_image" class="block mt-1 w-full">
                                </div>
                        
                                <h4 class="text-lg font-semibold mt-6">Answers</h4>
                                <div class="mt-4 space-y-3">
                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="flex items-center space-x-3 p-2 border rounded-md">
                                            <input type="radio" name="new_is_correct" value="{{ $i }}" required @if($i == 0) checked @endif>
                                            <div class="flex-grow">
                                                <select name="new_answers[{{ $i }}][type]" class="answer-type-selector w-full mb-2 rounded-md shadow-sm">
                                                    <option value="text" selected>Text</option>
                                                    <option value="image">Image</option>
                                                </select>
                                                <input type="text" name="new_answers[{{ $i }}][text]" placeholder="Answer {{ $i + 1 }} Text" class="answer-text-input w-full rounded-md shadow-sm">
                                                <input type="file" name="new_answers[{{ $i }}][image]" class="answer-image-input w-full mt-1" style="display: none;">
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
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

    {{-- MODIFIED: JavaScript to handle all text/image toggles --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // For the main question
            document.getElementById('questionTypeSelector').addEventListener('change', function () {
                const parent = this.closest('.new-question-section');
                const isText = this.value === 'text';
                parent.querySelector('#text-input-container').style.display = isText ? 'block' : 'none';
                parent.querySelector('#image-input-container').style.display = isText ? 'none' : 'block';
            });
    
            // For each of the answers
            document.querySelectorAll('.answer-type-selector').forEach(selector => {
                selector.addEventListener('change', function () {
                    const parent = this.closest('.flex-grow');
                    const isText = this.value === 'text';
                    parent.querySelector('.answer-text-input').style.display = isText ? 'block' : 'none';
                    parent.querySelector('.answer-image-input').style.display = isText ? 'none' : 'block';
                });
            });

            // Trigger the change events on page load to set the initial correct state
            document.getElementById('questionTypeSelector').dispatchEvent(new Event('change'));
            document.querySelectorAll('.answer-type-selector').forEach(s => s.dispatchEvent(new Event('change')));
        });
    </script>
</x-app-layout>