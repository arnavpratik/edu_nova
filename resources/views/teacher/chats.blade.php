{{-- resources/views/teacher/chats.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Chats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">Your Conversations</h3>
                    @forelse ($students as $student)
                        <div class="border-b p-4 hover:bg-gray-50">
                            <button onclick="openStudentChat({{ $student->id }})" class="text-lg text-blue-600 font-semibold w-full text-left">
                                {{ $student->name }}
                            </button>
                        </div>
                    @empty
                        <p class="text-gray-500">No students have started a chat with you yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function openStudentChat(studentId) {
        const chatUrl = `/chat/${studentId}`;
        const windowName = 'StudentChatWindow';
        const windowFeatures = 'width=400,height=600,scrollbars=yes,resizable=yes';
        window.open(chatUrl, windowName, windowFeatures);
    }
</script>