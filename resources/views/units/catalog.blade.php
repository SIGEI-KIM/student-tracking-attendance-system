<x-student-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-4xl text-gray-800 leading-tight border-b-4 border-indigo-600 pb-4 mb-4">
            {{ __('Unit Catalog') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Filter Units</h3>

                    <form id="filterForm" method="GET" action="{{ route('student.units.catalog.index') }}" class="mb-8 p-6 bg-indigo-50 rounded-xl shadow-inner">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                            <div>
                                <label for="year" class="block text-sm font-semibold text-gray-700 mb-1">Filter by Year</label>
                                <select id="year" name="year" class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base">
                                    <option value="">Select Year</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}" {{ (string)$selectedYear === (string)$year ? 'selected' : '' }}>
                                            Year {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="semester" class="block text-sm font-semibold text-gray-700 mb-1">Filter by Semester</label>
                                <select id="semester" name="semester" class="mt-1 block w-full px-4 py-2 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base">
                                    <option value="">Select Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester }}" {{ (string)$selectedSemester === (string)$semester ? 'selected' : '' }}>
                                            {{ $semester }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex space-x-4">
                                <button type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-base font-medium rounded-lg shadow-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                    <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-2 0V4H4v12h12v-2a1 1 0 112 0v3a1 1 0 01-1 1H3a1 1 0 01-1-1V3zm9.354 5.354a.5.5 0 00-.708-.708L8.5 10.293 6.854 8.646a.5.5 0 10-.708.708l2 2a.5.5 0 00.708 0l3-3z" clip-rule="evenodd"></path></svg>
                                    Apply Filters
                                </button>
                                @if ($selectedYear || $selectedSemester)
                                    <a href="{{ route('student.units.catalog.index') }}" class="flex-1 inline-flex items-center justify-center px-6 py-2.5 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                        <svg class="w-5 h-5 mr-2 -ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Clear Filters
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Available Units</h3>

                    {{-- NEW LOGIC HERE --}}
                    @if (!$selectedYear && !$selectedSemester)
                        {{-- Display message if no filters are selected initially --}}
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-5 rounded-md shadow-sm" role="alert">
                            <p class="font-bold text-lg mb-1">Please Select Filters</p>
                            <p class="text-md">Please select both a Year and a Semester to view available units.</p>
                        </div>
                    @else
                        {{-- Only show units or "No Units Found" if filters *have been* applied --}}
                        @if ($units->isEmpty())
                            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-5 rounded-md shadow-sm" role="alert">
                                <p class="font-bold text-lg mb-1">No Units Found</p>
                                <p class="text-md">No units match your selected filters. Please adjust your filters or ensure units are assigned to the selected academic period.</p>
                            </div>
                        @else
                            {{-- Display the table if units are found --}}
                            <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Unit Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Unit Code</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Course</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Level</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Semester</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lecturers</th>
                                            {{-- ADDED: New table header for Schedule --}}
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Schedule</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($units as $unit)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $unit->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $unit->code }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $unit->course->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $unit->level->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $unit->semester->name ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                    @forelse ($unit->lecturers as $lecturer)
                                                        {{ $lecturer->name }}{{ !$loop->last ? ', ' : '' }}
                                                    @empty
                                                        N/A
                                                    @endforelse
                                                </td>
                                                {{-- ADDED: New table data cell for Schedule --}}
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                    @forelse($unit->schedules as $schedule)
                                                        {{ \Carbon\Carbon::parse("Sunday")->addDays($schedule->day_of_week_numeric)->format('l') }}:
                                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                        @if (!$loop->last)
                                                            <br> {{-- Add a line break if there are multiple schedules --}}
                                                        @endif
                                                    @empty
                                                        No Schedule
                                                    @endforelse
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                    {{-- END NEW LOGIC --}}

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('filterForm').addEventListener('submit', function(event) {
                const year = document.getElementById('year').value;
                const semester = document.getElementById('semester').value;

                if (!year || !semester) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Filter Incomplete!',
                        text: 'Please select both a Year and a Semester to apply filters.',
                        confirmButtonText: 'Got It'
                    });
                }
            });

            // Removed the initial Swal.fire message for a cleaner initial state
            // The new Blade conditional logic handles the initial message display.
        </script>
    @endpush
</x-student-layout>