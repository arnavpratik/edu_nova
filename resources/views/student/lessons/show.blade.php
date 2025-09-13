{{-- resources/views/student/lessons/show.blade.php --}}
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
        {{-- ✅ Increased width from max-w-4xl to max-w-6xl --}}
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">
                    
                    <h3 class="text-3xl font-bold mb-6 text-center">{{ $lesson->title }}</h3>

                    <div class="prose max-w-none mb-8">
                        {!! nl2br(e($lesson->content)) !!}
                    </div>

                    {{-- ✅ Quiz + Doubt buttons in one line --}}
                    @if ($lesson->quiz)
                        <div class="flex justify-center gap-4 mb-8 flex-wrap">
                            <a href="{{ route('student.quizzes.take', $lesson->quiz) }}" 
                                class="inline-block bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 transition">
                                Ready? Start the Quiz!
                            </a>
                            <button onclick="openDoubtChat({{ $lesson->course->teacher_id }})" 
                                class="bg-green-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-green-700 transition">
                                Have a Doubt? Ask the Teacher Privately
                            </button>
                        </div>
                    @endif
                    
                    <div class="border-t pt-6">
                        <h3 class="text-2xl font-semibold mb-4">Public Discussion</h3>
                        
                        @if(!Auth::user()->is_blocked_from_discussion)
                            <form method="POST" action="{{ route('student.comments.store', $lesson) }}" class="mb-6">
                                @csrf
                                <textarea name="body" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ask a question or share your thoughts..." required></textarea>
                                <div class="flex justify-end mt-2">
                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Post</button>
                                </div>
                            </form>
                        @else
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                You have been blocked from participating in the discussion due to inappropriate comments.
                            </div>
                        @endif
                        
                        <div class="space-y-6">
                            @forelse ($lesson->comments()->latest()->get() as $comment)
                                <div class="flex space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600">
                                            {{ $comment->user->name[0] }}
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex items-baseline space-x-2">
                                            <p class="font-semibold">{{ $comment->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                        <p class="text-gray-800">{{ $comment->body }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 italic">No comments yet. Be the first to start the discussion!</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




{{-- ✅ START: SCRIPT FOR CHAT BUTTON --}}
<script>
    function openDoubtChat(teacherId) {
        const chatUrl = `/chat/${teacherId}`;
        const windowName = 'DoubtChatWindow';
        const windowFeatures = 'width=400,height=600,scrollbars=yes,resizable=yes';
        window.open(chatUrl, windowName, windowFeatures);
    }
</script>
{{-- ✅ END: SCRIPT FOR CHAT BUTTON --}}


<script>
    // Your existing engagement tracking script
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