<x-student-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-800 leading-tight border-b-2 border-indigo-600 pb-3">
            Manage Academic Progress
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                {{-- Filter Section --}}
                <div class="bg-gray-100 p-4 rounded-lg shadow-inner mb-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-3">Filter Courses</h4>
                    <form action="{{ route('student.academic.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                        {{-- Filter by Year --}}
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700">Filter by Year</label>
                            <select id="year" name="year" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Year</option>
                                @php
                                    $years = $allLevels->unique('year_number')->sortBy('year_number');
                                @endphp
                                @foreach($years as $level)
                                    <option value="{{ $level->year_number }}" {{ (string)$selectedYear === (string)$level->year_number ? 'selected' : '' }}>
                                        Year {{ $level->year_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter by Semester --}}
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700">Filter by Semester</label>
                            <select id="semester" name="semester" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">Select Semester</option>
                                @php
                                    // Filter semesters based on selected year if any, otherwise show all unique semesters
                                    $semesters = $allLevels->unique('semester_number')->sortBy('semester_number');
                                    if ($selectedYear) {
                                        $semesters = $allLevels->where('year_number', $selectedYear)->unique('semester_number')->sortBy('semester_number');
                                    }
                                @endphp
                                @foreach($semesters as $level)
                                    <option value="{{ $level->semester_number }}" {{ (string)$selectedSemester === (string)$level->semester_number ? 'selected' : '' }}>
                                        Semester {{ $level->semester_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Apply Filters Button --}}
                        <div class="flex space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Apply Filters
                            </button>
                            @if($selectedYear || $selectedSemester)
                                <a href="{{ route('student.academic.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <h3 class="text-2xl font-semibold text-gray-700 mb-4">Your Enrolled Courses</h3>

                @if($enrolledCourses->isEmpty())
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4" role="alert">
                        <p class="font-bold">No Courses Found</p>
                        <p>No enrolled courses match your selected filters. Please adjust your filters or ensure you are enrolled in courses assigned to the selected level.</p>
                    </div>
                @else
                    <ul class="list-disc list-inside space-y-2">
                        @foreach($enrolledCourses as $course)
                            <li>
                                <span class="font-medium text-indigo-700">{{ $course->name }}</span>
                                @if($course->pivot && $course->pivot->level_id)
                                    @php
                                        $level = App\Models\Level::find($course->pivot->level_id);
                                    @endphp
                                    @if($level)
                                        <span class="text-gray-600">- Level: {{ $level->name }} (Year {{ $level->year_number }} Semester {{ $level->semester_number }})</span>
                                    @else
                                        <span class="text-red-500">- Assigned level (ID: {{ $course->pivot->level_id }}) not found in the system.</span>
                                    @endif
                                @else
                                    <span class="text-yellow-600">- Academic level not yet assigned.</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

                <h3 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">Academic Resources</h3>
                <p class="text-gray-600">
                    This section can be expanded to include links to academic calendars,
                    student handbooks, lecture schedules, and other important academic resources.
                </p>

                {{-- Add more sections here as needed --}}

            </div>
        </div>
    </div>
</x-student-layout>