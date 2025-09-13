{{-- student/quizzes/take.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Take Quiz: {{ $quiz->title ?? 'Quiz' }}
            </h2>
            <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                &laquo; Back to Lesson
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200" x-data="{ q: 0, answers: {} }">
                    <form action="{{ route('student.quizzes.submit', $quiz->id) }}" method="POST" x-ref="quizForm">
                        @csrf
                        @foreach ($quiz->questions as $index => $question)
                            <div x-show="q === {{ $index }}">
                                <h3 class="text-lg font-bold mb-2">Question {{ $index + 1 }}:</h3>

                                {{-- ✅ KEY FIX: Display Question (Text or Image) --}}
                                <div class="mb-4 text-gray-800 text-xl">
                                    @if ($question->type === 'text')
                                        <p>{{ $question->content }}</p>
                                    @else
                                        <img src="{{ Storage::url($question->content) }}" alt="Question Image" class="max-w-md rounded-lg shadow">
                                    @endif
                                </div>

                                <div class="space-y-3 mb-6">
                                    @foreach ($question->answers as $answer)
                                        <label class="flex items-center cursor-pointer space-x-4 p-3 rounded-md border-2 hover:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-500">
                                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" required @change="answers['{{ $question->id }}'] = '{{ $answer->id }}'" class="form-radio text-blue-600 focus:ring-blue-500 h-5 w-5"/>

                                            {{-- ✅ KEY FIX: Display Answer (Text or Image) --}}
                                            <div>
                                                @if ($answer->type === 'text')
                                                    <span>{{ $answer->content }}</span>
                                                @else
                                                    <img src="{{ Storage::url($answer->content) }}" alt="Answer Image" class="h-24 w-auto rounded">
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                {{-- Navigation --}}
                                <div class="flex justify-between items-center">
                                     <span class="text-sm text-gray-500">Question {{ $index + 1 }} of {{ count($quiz->questions) }}</span>
                                     <div>
                                         @if ($index < count($quiz->questions) - 1)
                                             <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded transition disabled:opacity-50" @click="q++" :disabled="!answers['{{ $question->id }}']">
                                                 Next &raquo;
                                             </button>
                                         @else
                                             <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded transition disabled:opacity-50" :disabled="!answers['{{ $question->id }}']">
                                                 Finish Quiz
                                             </button>
                                         @endif
                                     </div>
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>