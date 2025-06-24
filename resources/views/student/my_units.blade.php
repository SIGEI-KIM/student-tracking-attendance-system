<x-student-layout>
    {{-- Optionally, you can define a header slot if your x-student-layout component uses it --}}
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-800 leading-tight border-b-2 border-indigo-500 pb-2">
            My Registered Units
        </h2>
    </x-slot>

    {{-- The main content for the my_units page --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- The h1 title from before is now part of the header slot, or you can keep it here --}}
                    {{-- <h1 class="text-3xl font-bold mb-6">My Registered Units</h1> --}}

                    {{-- Filter Form --}}
                    <form action="{{ route('student.my-units') }}" method="GET" class="mb-6 bg-gray-50 p-4 rounded-lg shadow-sm flex flex-wrap gap-4 items-end">
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Select Year:</label>
                            <select name="year" id="year" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">All Years</option>
                                @foreach ($availableYears as $year)
                                    <option value="{{ $year }}" @selected($selectedYear == $year)>Year {{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-1">Select Semester:</label>
                            <select name="semester" id="semester" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="">All Semesters</option>
                                @foreach ($availableSemesters as $semester)
                                    <option value="{{ $semester }}" @selected($selectedSemester == $semester)>Semester {{ $semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Apply Filters
                            </button>
                            @if ($selectedYear || $selectedSemester)
                                <a href="{{ route('student.my-units') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    </form>
                    {{-- End Filter Form --}}

                    @if ($units->isEmpty())
                        <p class="text-gray-600">No units found matching your criteria. Please adjust your filters or enroll in a course.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($units as $unit)
                                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                                    <h3 class="text-xl font-semibold mb-2 text-indigo-700">{{ $unit->name }}</h3>
                                    <p class="text-gray-600 mb-2">Code: <span class="font-medium">{{ $unit->code }}</span></p>
                                    @if($unit->level)
                                        <p class="text-gray-600 mb-2">Level: <span class="font-medium">{{ $unit->level->name }}</span></p>
                                        @if($unit->level->year_number && $unit->level->semester_number)
                                            <p class="text-gray-600 mb-4">
                                                Academic Year: <span class="font-medium">{{ $unit->level->year_number }}</span>,
                                                Semester: <span class="font-medium">{{ $unit->level->semester_number }}</span>
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-student-layout>