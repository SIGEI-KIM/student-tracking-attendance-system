@extends('layouts.student')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                {{-- Updated header to conditionally display course/level, using parameters passed from controller --}}
                <h2 class="text-2xl font-bold mb-6">
                    Units
                    @if(isset($course) && $course)
                        for {{ $course->name }}
                    @endif
                    @if(isset($level) && $level)
                        ({{ $level->name }})
                    @endif
                </h2>

                {{-- Session Messages Container (You might want to ensure this is present in your actual file) --}}
                <div class="space-y-4 mb-6">
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="bg-blue-50 border border-blue-400 text-blue-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                            <strong class="font-bold">Info:</strong>
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="bg-yellow-50 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                            <strong class="font-bold">Warning!</strong>
                            <span class="block sm:inline">{{ session('warning') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                </div>

                {{-- Filter Section (from previous suggested my_units.blade.php) --}}
                <div class="mb-6 p-4 bg-gray-100 rounded-lg shadow-inner">
                    <form action="{{ route('student.my-units') }}" method="GET" class="flex flex-wrap items-center gap-4">
                        <label for="year" class="font-medium text-gray-700">Filter by Year:</label>
                        <select name="year" id="year" class="form-select mt-1 block w-full md:w-auto rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All Years</option>
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" @if((string)$year === (string)$selectedYear) selected @endif>{{ $year }}</option>
                            @endforeach
                        </select>

                        <label for="semester" class="font-medium text-gray-700">Filter by Semester:</label>
                        <select name="semester" id="semester" class="form-select mt-1 block w-full md:w-auto rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All Semesters</option>
                            @foreach($availableSemesters as $semester)
                                <option value="{{ $semester }}" @if((string)$semester === (string)$selectedSemester) selected @endif>Semester {{ $semester }}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Apply Filters
                        </button>
                        <a href="{{ route('student.my-units') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Clear Filters
                        </a>
                    </form>
                </div>

                @if($units->isEmpty())
                    <div class="bg-blue-50 border border-blue-400 text-blue-700 px-4 py-3 rounded-md relative shadow-sm text-center" role="alert">
                        <p class="font-bold text-lg">No units found matching your criteria.</p>
                        <p class="text-sm">Please ensure you have enrolled in a course with units assigned to it, or adjust your filters.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($units as $unit)
                            <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                                <h4 class="text-lg font-bold mb-2">{{ $unit->unit_name }}</h4> {{-- Use unit_name as per DB --}}
                                <p class="text-gray-600 mb-4">{{ $unit->unit_code }}</p> {{-- Use unit_code as per DB --}}

                                <div class="mb-4">
                                    <h5 class="font-semibold">Course: <span class="font-normal">{{ $unit->course->name ?? 'N/A' }}</span></h5>
                                    <h5 class="font-semibold">Level: <span class="font-normal">{{ $unit->level->name ?? 'N/A' }}</span></h5>
                                    @if($unit->lecturers->isNotEmpty())
                                        <h5 class="font-semibold mt-2">Lecturers:</h5>
                                        <ul class="list-disc list-inside text-sm text-gray-600">
                                            @foreach($unit->lecturers as $lecturer)
                                                <li>{{ $lecturer->full_name ?? $lecturer->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-sm text-gray-500 italic">No lecturers assigned.</p>
                                    @endif
                                </div>

                                {{-- Removed the attendance link for now --}}
                                {{-- <a href="{{ route('student.mark-attendance', $unit) }}"
                                   class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Mark Attendance
                                </a> --}}
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $units->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection