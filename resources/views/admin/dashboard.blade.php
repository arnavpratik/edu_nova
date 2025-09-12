<x-app-layout>
    {{-- Header section for the dashboard page --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    {{-- Main content area --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Title for the overview section --}}
            <div class="mb-6">
                <h3 class="text-2xl font-semibold text-gray-700">Dashboard Overview</h3>
                <p class="text-gray-500">A high-level overview of the EduNova platform.</p>
            </div>

            {{-- Grid container for the statistics cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center transition-transform transform hover:scale-105">
                    <div class="text-5xl font-extrabold text-blue-500">{{ $studentCount }}</div>
                    <div class="mt-2 text-lg font-medium text-gray-600">Total Students</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center transition-transform transform hover:scale-105">
                    <div class="text-5xl font-extrabold text-green-500">{{ $teacherCount }}</div>
                    <div class="mt-2 text-lg font-medium text-gray-600">Total Teachers</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center transition-transform transform hover:scale-105">
                    <div class="text-5xl font-extrabold text-purple-500">{{ $courseCount }}</div>
                    <div class="mt-2 text-lg font-medium text-gray-600">Total Courses</div>
                </div>

            </div>

            <div class="mt-8 bg-white shadow-sm rounded-lg">
                <div class="p-6">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Management Actions</h3>
                    <div class="flex">
                        <a href="{{ route('admin.users.index') }}" class="inline-block bg-gray-800 text-white font-bold py-3 px-5 rounded-lg hover:bg-gray-700 transition">
                            Manage Users
                        </a>
                    </div>
                </div>
            </div>
            </div>
    </div>
</x-app-layout>