{{-- resources/views/lecturer/attendance/unit_attendances.blade.php --}}
@extends('layouts.lecturer') {{-- Or your appropriate lecturer layout, make sure it pulls in Tailwind CSS --}}

@section('content')
    <div class="py-12"> {{-- Main container for spacing like other Laravel pages --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Attendance Details for <span class="text-indigo-600">{{ $unit->name }}</span> ({{ $unit->code }})</h1>
                        {{-- Optional: Back button --}}
                        <a href="{{ route('lecturer.attendance.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">
                            Back to Overview
                        </a>
                    </div>

                    <div class="mb-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                        <p class="text-gray-700 mb-1"><strong>Course:</strong> <span class="font-medium">{{ $unit->course->name ?? 'N/A' }}</span></p>
                        <p class="text-gray-700 mb-1"><strong>Level:</strong> <span class="font-medium">{{ $unit->level->name ?? 'N/A' }}</span></p>
                        <p class="text-gray-700"><strong>Scheduled Days:</strong>
                            @forelse($scheduledDays as $schedule)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full mr-2 mb-1">
                                    {{ \Carbon\Carbon::today()->startOfWeek()->addDays($schedule->day_of_week_numeric)->format('l') }} ({{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }})
                                </span>{{ !$loop->last ? '' : '' }} {{-- No comma needed if using pills --}}
                            @empty
                                <span class="text-gray-500 italic">No schedule defined.</span>
                            @endforelse
                        </p>
                    </div>

                    <h2 class="text-xl font-semibold text-gray-700 mb-4">All Attendance Records</h2>

                    @if ($attendances->isEmpty())
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md" role="alert">
                            <p class="font-bold">No Records</p>
                            <p>No attendance records found for this unit yet.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto shadow-md sm:rounded-lg border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            Student Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            Reg. Number
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            Marked At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($attendances as $record)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $record->student->user->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $record->student->registration_number ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $record->attendance_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">
                                                @if ($record->status == 'present')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Present
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Absent
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $record->marked_at->format('M d, Y H:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $attendances->links() }} {{-- This assumes you are using Laravel's default Tailwind pagination views --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection