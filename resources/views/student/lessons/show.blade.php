<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <a href="{{ route('student.courses.show', $lesson->course) }}" class="inline-block bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                &larr; Back to Course
            </a>
            
            <div class="flex space-x-2">
                @if ($previousLesson)
                    <a href="{{ route('student.lessons.show', $previousLesson) }}" class="inline-block bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                        &larr; Previous
                    </a>
                @endif
                
                @if ($nextLesson)
                    <a href="{{ route('student.lessons.show', $nextLesson) }}" class="inline-block bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                        Next &rarr;
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12" data-lesson-id="{{ $lesson->id }}">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    <h3 class="text-3xl font-bold mb-6 text-center">{{ $lesson->title }}</h3>

                    <div class="prose max-w-none">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>

                    <div class="mt-8 border-t pt-6">
                        <h4 class="text-xl font-bold mb-4">Ready to test your knowledge?</h4>
                        @if ($lesson->quiz)
                            <a href="{{ route('student.quizzes.take', $lesson->quiz) }}" 
                               class="inline-block bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 transition">
                                Start Quiz
                            </a>
                        @else
                            <p class="text-gray-500 italic">No quiz available for this lesson yet.</p>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lessonElement = document.querySelector('[data-lesson-id]');
        if (!lessonElement) return;
        const lessonId = lessonElement.dataset.lessonId;
        
        //CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let activeTime = 0;
        let idleTime = 0;
        let tabSwitches = 0;
        let lastActivity = Date.now();
        const idleTimeout = 30000; // 30 seconds

        // tracking logic
        setInterval(() => {
            if (Date.now() - lastActivity > idleTimeout) idleTime++;
            else activeTime++;
        }, 1000);
        ['mousemove', 'keydown', 'scroll', 'click', 'touchstart'].forEach(event => {
            document.addEventListener(event, () => lastActivity = Date.now());
        });
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') tabSwitches++;
        });


        
        const sendData = () => {
            if (activeTime === 0 && idleTime === 0 && tabSwitches === 0) return;

            const data = {
                lesson_id: lessonId,
                active_seconds: activeTime,
                idle_seconds: idleTime,
                tab_switches: tabSwitches,
                _token: csrfToken
            };
            
          
            const blob = new Blob([JSON.stringify(data)], { type: 'application/json' });
            
            
            navigator.sendBeacon("{{ route('api.track-engagement') }}", blob);
            
            // Reset counters
            activeTime = 0;
            idleTime = 0;
            tabSwitches = 0;
        };
        
        // Send data
        setInterval(sendData, 30000); 
        window.addEventListener('beforeunload', sendData);
    });
</script>