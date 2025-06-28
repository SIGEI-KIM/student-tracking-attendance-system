@extends('layouts.lecturer') {{-- EXTENDS YOUR BASE LECTURER LAYOUT --}}

@section('content')
<div class="py-6 sm:py-8 md:py-10 lg:py-12"> {{-- Adjusted vertical padding for all screen sizes --}}
    <div class="max-w-xl sm:max-w-3xl md:max-w-5xl lg:max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> {{-- Responsive max-width and horizontal padding --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"> {{-- Larger shadow, slightly rounded corners --}}
            <div class="p-4 sm:p-6 lg:p-8 bg-white border-b border-gray-200"> {{-- Responsive padding inside the card --}}
                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 sm:mb-6">Lecturer Dashboard</h2> {{-- Responsive font size and heavier font-weight --}}

                {{-- Session Messages Container --}}
                <div class="space-y-3 sm:space-y-4 mb-5 sm:mb-6"> {{-- Responsive spacing --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-500 text-green-800 px-4 py-3 rounded-lg relative shadow-md" role="alert"> {{-- Darker green, more shadow --}}
                            <strong class="font-semibold">Success!</strong> {{-- Changed from bold to semibold --}}
                            <span class="block sm:inline text-sm sm:text-base">{{ session('success') }}</span> {{-- Responsive text size --}}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-500 text-red-800 px-4 py-3 rounded-lg relative shadow-md" role="alert"> {{-- Darker red, more shadow --}}
                            <strong class="font-semibold">Error!</strong>
                            <span class="block sm:inline text-sm sm:text-base">{{ session('error') }}</span>
                        </div>
                    @endif
                </div>

                {{-- Your Assigned Units Section --}}
                <div class="bg-gray-50 p-5 sm:p-6 md:p-8 rounded-xl shadow-inner border border-gray-100 mb-8 lg:mb-10"> {{-- Lighter background, inner shadow, larger border-radius --}}
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-5 border-b-2 pb-3 border-indigo-300 flex items-center"> {{-- Thicker border-bottom --}}
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-indigo-600 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                        Your Assigned Units
                    </h3>

                    @if($units->isEmpty())
                        <div class="bg-blue-100 border border-blue-500 text-blue-800 px-5 py-4 sm:px-6 sm:py-5 rounded-lg relative shadow-md text-center">
                            <p class="text-base sm:text-lg mb-2 sm:mb-3">
                                <strong class="font-bold">You are not assigned to any units yet.</strong>
                            </p>
                            <p class="text-sm sm:text-md italic text-blue-700">Please contact the administrator for unit assignments.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6"> {{-- Responsive grid columns and gap --}}
                            @foreach($units as $unit)
                                {{-- ENHANCED CARD STYLING --}}
                                <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-5 sm:p-6 transform transition duration-300 hover:scale-103 hover:shadow-2xl relative overflow-hidden flex flex-col justify-between">
                                    {{-- Top Border Gradient --}}
                                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-t-xl"></div>

                                    <div>
                                        <h4 class="text-xl sm:text-2xl font-extrabold text-gray-900 mb-2 mt-2">
                                            {{ $unit->name }}
                                        </h4>
                                        <p class="text-gray-700 text-sm mb-1">Code: <span class="font-semibold text-gray-800">{{ $unit->code }}</span></p>

                                        @if($unit->course)
                                            <p class="text-gray-700 text-sm mb-1">Course: <span class="font-semibold text-blue-700">{{ $unit->course->name }}</span></p>
                                        @endif
                                        @if($unit->level)
                                            <p class="text-gray-700 text-sm mb-3">Level: <span class="font-semibold text-green-700">{{ $unit->level->name }}</span></p>
                                        @endif

                                        {{-- SCHEDULE SECTION --}}
                                        <p class="text-gray-700 text-sm font-semibold mb-2 mt-4">Class Schedule:</p>
                                        @if($unit->schedules->isNotEmpty())
                                            <div class="space-y-1"> {{-- Added space between schedule entries --}}
                                                @foreach($unit->schedules as $schedule)
                                                    <span class="flex items-center text-xs font-medium text-purple-700 bg-purple-100 rounded-full px-3 py-1 shadow-sm w-fit">
                                                        <svg class="w-3 h-3 mr-1.5 text-purple-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                        {{ \Carbon\Carbon::create()->dayOfWeek($schedule->day_of_week_numeric)->format('l') }}:
                                                        <span class="ml-1 font-bold">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-600 text-xs italic">No schedule defined.</p>
                                        @endif
                                    </div>

                                    {{-- View Attendances Button --}}
                                    <div class="mt-6 text-center"> {{-- Centered and more top margin --}}
                                        <a href="{{ route('lecturer.attendance.unit.view', ['unit' => $unit->id]) }}"
                                           class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12l-3 3m0 0l-3-3m3 3V2.25M2.25 9v12a2.25 2.25 0 002.25 2.25h15a2.25 2.25 0 002.25-2.25V9M2.25 9h19.5" /></svg>
                                            View Attendances
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Today's Attendances Section --}}
                @if(!$todayAttendances->isEmpty())
                    <h3 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-5 border-b-2 pb-3 border-indigo-300 flex items-center">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-indigo-600 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Today's Attendances
                    </h3>
                    <div class="bg-gray-100 p-5 sm:p-6 rounded-xl shadow-inner border border-gray-200"> {{-- Lighter background, inner shadow --}}
                        @foreach($todayAttendances as $unitId => $attendances)
                            @php
                                $unit = $units->firstWhere('id', $unitId);
                                $todayNumeric = \Carbon\Carbon::now()->dayOfWeek;
                                $todaysSchedule = $unit->schedules->firstWhere('day_of_week_numeric', $todayNumeric);
                            @endphp
                            <div class="mb-5 sm:mb-6 border-b border-gray-300 pb-4 last:border-b-0"> {{-- Darker border --}}
                                <h4 class="font-semibold text-md sm:text-lg mb-3 text-gray-900">
                                    {{ $unit->name }} ({{ $unit->code }})
                                    @if($todaysSchedule)
                                        <span class="text-sm font-normal text-gray-600">
                                            ({{ \Carbon\Carbon::parse($todaysSchedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($todaysSchedule->end_time)->format('H:i') }})
                                        </span>
                                    @endif
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4"> {{-- More responsive grid columns --}}
                                    @foreach($attendances as $attendance)
                                        <div class="bg-white p-3 sm:p-4 rounded-lg border shadow-sm flex flex-col justify-between
                                            {{ $attendance->status === 'present' ? 'border-green-300 bg-green-50' :
                                               ($attendance->status === 'late' ? 'border-yellow-300 bg-yellow-50' :
                                               'border-red-300 bg-red-50') }}"> {{-- Slightly darker borders for status --}}
                                            <div>
                                                <p class="font-medium text-gray-800 text-sm sm:text-base truncate">{{ $attendance->student->user->name ?? 'N/A' }}</p>
                                                <p class="text-xs sm:text-sm capitalize text-gray-600">Status: <span class="font-semibold">{{ $attendance->status }}</span></p>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">Marked At: {{ $attendance->marked_at->format('h:i A') }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-blue-100 border border-blue-500 text-blue-800 px-5 py-4 sm:px-6 sm:py-5 rounded-lg relative shadow-md text-center mt-6 sm:mt-8">
                        <p class="text-base sm:text-lg mb-2 sm:mb-3">
                            <strong class="font-bold">No attendance recorded for your units today.</strong>
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection