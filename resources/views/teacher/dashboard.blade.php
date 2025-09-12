{{-- resources/views/teacher/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teacher Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold">My Courses</h3>
                        <a href="{{ route('teacher.courses.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            + Create New
                        </a>
                    </div>

                    @forelse ($courses as $course)
                    <div class="bg-gray-50 hover:bg-gray-100 p-5 rounded-lg shadow transition mb-5">
                        <h4 class="font-bold text-xl text-gray-800">{{ $course->title }}</h4>
                        <p class="text-gray-600 mt-2">{{ $course->description }}</p>

                        <div class="mt-4 flex gap-4">
                            <a href="{{ route('teacher.courses.show', $course) }}"
                                class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('teacher.courses.edit', $course) }}"
                                class="text-green-600 hover:underline">Edit</a>
                            <a href="{{ route('teacher.courses.analytics', $course) }}" class="text-purple-600 hover:underline">Analytics</a>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500">You haven’t created any courses yet.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>