<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analytics for: {{ $course->title }}
            </h2>

            <a href="{{ route('teacher.dashboard') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                &laquo; Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">Engagement Overview (Time in Minutes)</h3>
                    <canvas id="engagementChart"></canvas>
                </div>
            </div>

            <!-- THIS IS THE UPDATED STUDENT PERFORMANCE TABLE -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">Student Performance</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg. Quiz Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Engagement</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($studentPerformanceData as $studentData)
                                    <tr>
                                        <td class="px-6 py-4 font-medium">{{ $studentData['name'] }}</td>
                                        <td class="px-6 py-4 font-bold">
                                            {{-- THIS IS THE CHANGED LOGIC --}}
                                            @if ($studentData['average_score'] !== null)
                                                <span class="{{ $studentData['average_score'] < 70 ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ $studentData['average_score'] }}%
                                                </span>
                                            @else
                                                <span class="text-gray-500 italic">Not Attempted</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $studentData['total_engagement_minutes'] }} min</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No students are enrolled in this course yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END OF UPDATED TABLE -->

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-semibold mb-4">Engagement Data by Lesson</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lesson Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Active Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Idle Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tab Switches</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($engagementData as $data)
                                    <tr>
                                        <td class="px-6 py-4 font-medium">{{ $data->lesson->title ?? 'Lesson not found' }}</td>
                                        <td class="px-6 py-4">{{ round($data->total_active_seconds / 60, 1) }} min</td>
                                        <td class="px-6 py-4">{{ round($data->total_idle_seconds / 60, 1) }} min</td>
                                        <td class="px-6 py-4">{{ $data->total_tab_switches }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No engagement data recorded for this course yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('engagementChart').getContext('2d');
            const engagementData = @json($engagementData);

            const labels = engagementData.map(d => d.lesson.title);
            const activeData = engagementData.map(d => d.total_active_seconds / 60);
            const idleData = engagementData.map(d => d.total_idle_seconds / 60);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Active Time (Minutes)',
                            data: activeData,
                            backgroundColor: 'rgba(59, 130, 246, 0.5)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Idle Time (Minutes)',
                            data: idleData,
                            backgroundColor: 'rgba(239, 68, 68, 0.5)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: { 
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Time (Minutes)'
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>