@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"> {{-- Larger shadow for the main container --}}
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-3xl font-extrabold mb-8 text-gray-800 border-b-2 border-indigo-500 pb-2">Admin Dashboard Overview</h2> {{-- Larger, bolder title with a bottom border --}}

                {{-- STATS GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10"> {{-- Increased bottom margin --}}
                    {{-- Student Card - Vibrant Teal --}}
                    <div class="bg-teal-100 p-6 rounded-xl shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-xl cursor-pointer flex flex-col justify-between border border-teal-200"> {{-- Rounded-xl, larger shadow, hover effect, border --}}
                        <div>
                            <h3 class="text-lg font-semibold text-teal-800 mb-2">Total Students</h3> {{-- More specific title --}}
                            <p class="text-4xl font-bold text-teal-900 mb-4">{{ $stats['students'] ?? 0 }}</p>
                        </div>
                        <a href="{{ route('admin.users.index', ['role' => 'student']) }}" class="text-teal-700 hover:text-teal-900 font-semibold text-sm flex items-center justify-end group"> {{-- Group for hover effect on text/icon --}}
                            View All
                            <svg class="w-5 h-5 ml-1 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>

                    {{-- Lecturer Card - Fresh Green (Emerald) --}}
                    <div class="bg-emerald-100 p-6 rounded-xl shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-xl cursor-pointer flex flex-col justify-between border border-emerald-200">
                        <div>
                            <h3 class="text-lg font-semibold text-emerald-800 mb-2">Total Lecturers</h3>
                            <p class="text-4xl font-bold text-emerald-900 mb-4">{{ $stats['lecturers'] ?? 0 }}</p>
                        </div>
                        <a href="{{ route('admin.lecturers.index') }}" class="text-emerald-700 hover:text-emerald-900 font-semibold text-sm flex items-center justify-end group">
                            View All
                            <svg class="w-5 h-5 ml-1 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>

                    {{-- Courses Card - Calm Blue (Sky) --}}
                    <div class="bg-sky-100 p-6 rounded-xl shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-xl cursor-pointer flex flex-col justify-between border border-sky-200">
                        <div>
                            <h3 class="text-lg font-semibold text-sky-800 mb-2">Total Courses</h3>
                            <p class="text-4xl font-bold text-sky-900 mb-4">{{ $stats['courses'] ?? 0 }}</p>
                        </div>
                        <a href="{{ route('admin.courses.index') }}" class="text-sky-700 hover:text-sky-900 font-semibold text-sm flex items-center justify-end group">
                            View All
                            <svg class="w-5 h-5 ml-1 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>

                    {{-- Units Card - Warm Orange (Amber) --}}
                    <div class="bg-amber-100 p-6 rounded-xl shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-xl cursor-pointer flex flex-col justify-between border border-amber-200">
                        <div>
                            <h3 class="text-lg font-semibold text-amber-800 mb-2">Total Units</h3>
                            <p class="text-4xl font-bold text-amber-900 mb-4">{{ $stats['units'] ?? 0 }}</p>
                        </div>
                        <a href="{{ route('admin.units.index') }}" class="text-amber-700 hover:text-amber-900 font-semibold text-sm flex items-center justify-end group">
                            View All
                            <svg class="w-5 h-5 ml-1 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>

                {{-- RECENT ACTIVITY SECTION --}}
                <div class="bg-gray-100 p-6 rounded-xl shadow-lg mb-10 border border-gray-200">
                    <h3 class="text-xl font-semibold mb-6 text-gray-800 border-b border-gray-300 pb-3">Recent Activity</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        {{-- Left Column: Latest Lecturer Registrations --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-6 h-6 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM14 15v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm0 0a2 2 0 01-2-2V9a2 2 0 012-2h2"></path></svg>
                                Latest Lecturer Registrations
                            </h4>
                            
                            @if($latestLecturerRegistrations->isEmpty())
                                <div class="text-gray-600 text-sm italic bg-white p-4 rounded-lg shadow-sm border border-gray-200">No recent lecturer registrations.</div>
                            @else
                                <ul class="space-y-3">
                                    @foreach($latestLecturerRegistrations as $lecturer)
                                        <li class="bg-white p-3 rounded-lg shadow-sm border border-emerald-100 transform transition duration-200 hover:shadow-md hover:translate-y-[-2px]">
                                            <p class="font-medium text-gray-800">{{ $lecturer->name }}</p>
                                            <p class="text-gray-500 text-xs">Email: {{ $lecturer->email }}</p>
                                            <p class="text-gray-500 text-xs">Registered: {{ $lecturer->created_at->diffForHumans() }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        {{-- Right Column: Recently Added Courses --}}
                        <div>
                            <h4 class="text-lg font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-6 h-6 text-sky-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                Recently Added Courses
                            </h4>
                            @if($recentlyAddedCourses->isEmpty())
                                <div class="text-gray-600 text-sm italic bg-white p-4 rounded-lg shadow-sm border border-gray-200">No courses added recently.</div>
                            @else
                                <ul class="space-y-3">
                                    @foreach($recentlyAddedCourses as $course)
                                        <li class="bg-white p-3 rounded-lg shadow-sm border border-sky-100 transform transition duration-200 hover:shadow-md hover:translate-y-[-2px]">
                                            <p class="font-medium text-gray-800">{{ $course->name }}</p>
                                            @if($course->code)
                                                <p class="text-gray-500 text-xs">Code: {{ $course->code }}</p>
                                            @endif
                                            <p class="text-gray-500 text-xs">Added: {{ $course->created_at->diffForHumans() }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div> {{-- End of grid for recent activity items --}}
                </div>

                {{-- Pie Chart for Gender Distribution - At the very bottom --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-700 mb-6 border-b border-gray-300 pb-3">Student Gender Distribution</h3>
                    @if(($totalStudents ?? 0) > 0)
                        <div class="relative h-80 w-full max-w-lg mx-auto">
                            <canvas id="genderPieChart"></canvas>
                        </div>
                        <div class="mt-8 text-base text-gray-700 text-center">
                            @foreach($genderPercentages as $label => $percentage)
                                <p>{{ $label }}: <span class="font-bold text-lg text-indigo-700">{{ $percentage }}%</span></p>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 text-gray-500 italic">
                            No student gender data available yet to display the chart.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const totalStudents = {{ $totalStudents ?? 0 }};
        if (totalStudents > 0) {
            const ctx = document.getElementById('genderPieChart');
            if (ctx) {
                const chartLabels = @json($chartLabels);
                const chartData = @json($chartData);
                const chartColors = @json($chartColors);
                const chartBorderColors = @json($chartBorderColors);
                const genderPercentages = @json($genderPercentages);

                const formattedLabels = chartLabels.map(label => {
                    const percentage = genderPercentages[label] !== undefined ? genderPercentages[label] : 0;
                    return `${label} (${percentage}%)`;
                });

                new Chart(ctx.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: formattedLabels,
                        datasets: [{
                            label: 'Student Gender',
                            data: chartData,
                            backgroundColor: chartColors,
                            borderColor: chartBorderColors,
                            borderWidth: 1.5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (context.parsed !== null) {
                                            label = `${chartLabels[context.dataIndex]}: ${context.parsed} students (${genderPercentages[chartLabels[context.dataIndex]]}%)`;
                                        }
                                        return label;
                                    }
                                }
                            },
                            legend: {
                                position: 'right',
                                labels: {
                                    font: {
                                        size: 14
                                    },
                                    boxWidth: 20
                                }
                            }
                        }
                    }
                });
            }
        }
    });
</script>
@endpush
@endsection