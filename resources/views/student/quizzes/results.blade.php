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
                    
                    @foreach ($results as $result)
                        <div class="mb-6 p-4 rounded-lg border 
                            @if ($result->is_correct)
                                bg-green-50 border-green-300
                            @else
                                bg-red-50 border-red-300
                            @endif
                        ">
                            <p class="font-semibold text-lg text-gray-800 mb-3">{{ $loop->iteration }}. {{ $result->question_text }}</p>
                            
                            <div class="text-sm space-y-2 pl-4">
                                <p>Your answer: 
                                    <span class="font-medium p-1 rounded {{ $result->is_correct ? 'bg-green-100' : 'bg-red-100' }}">
                                        {{ $result->submitted_answer }}
                                    </span>
                                </p>

                                @if (!$result->is_correct)
                                    <p class="text-green-700">Correct answer: 
                                        <span class="font-medium p-1 rounded bg-green-100">
                                            {{ $result->correct_answer }}
                                        </span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach

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