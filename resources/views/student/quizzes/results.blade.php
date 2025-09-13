{{-- student/quizzes/results.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Quiz Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="bg-blue-50 p-6 rounded-lg text-center mb-8 shadow-sm">
                        <h3 class="text-xl font-medium text-gray-800">Your Score</h3>
                        <p class="text-6xl font-extrabold {{ $attempt->score >= 70 ? 'text-green-600' : 'text-red-600' }} mt-2">
                            {{ $attempt->score }}%
                        </p>
                    </div>

                    <h3 class="text-2xl font-semibold mb-4">Answer Review</h3>
                    
                    {{-- MODIFIED: Rewritten to use full objects and relationships --}}
                    <div class="space-y-8">
                        @foreach ($attempt->quiz->questions as $question)
                            <div>
                                {{-- Display Question (Text or Image) --}}
                                <div class="question-content mb-4">
                                    @if ($question->type === 'text')
                                        <p class="text-lg font-semibold">{{ $loop->iteration }}. {{ $question->content }}</p>
                                    @else
                                        <p class="text-lg font-semibold mb-2">{{ $loop->iteration }}.</p>
                                        <img src="{{ Storage::url($question->content) }}" alt="Question" class="max-w-sm rounded-lg">
                                    @endif
                                </div>

                                {{-- Loop through answers to display and style them --}}
                                <div class="space-y-2 pl-4">
                                    @foreach ($question->answers as $answer)
                                        @php
                                            $userAnswerId = $userAnswers[$question->id] ?? null;
                                            $isCorrect = $answer->is_correct;
                                            $isSelected = ($userAnswerId == $answer->id);
                                            
                                            $class = 'border-gray-300'; // Default
                                            if ($isCorrect) {
                                                $class = 'border-green-500 bg-green-50 text-green-800'; // Correct answer
                                            } elseif ($isSelected && !$isCorrect) {
                                                $class = 'border-red-500 bg-red-50 text-red-800'; // User's wrong answer
                                            }
                                        @endphp

                                        <div class="flex items-center p-3 border-2 rounded-md {{ $class }}">
                                            <span class="font-bold mr-3 text-lg">
                                                @if ($isCorrect) ✔ @elseif($isSelected && !$isCorrect) ✖ @else ● @endif
                                            </span>
                                            
                                            {{-- Display Answer (Text or Image) --}}
                                            @if ($answer->type === 'text')
                                                <span>{{ $answer->content }}</span>
                                            @else
                                                <img src="{{ Storage::url($answer->content) }}" alt="Answer" class="h-20 w-auto rounded-md">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-center mt-8">
                        <a href="{{ route('student.dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition duration-300">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>