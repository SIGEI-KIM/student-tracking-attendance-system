@extends('layouts.lecturer') 

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">Attendance Overview</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($units as $unit)
                        <div class="border rounded-lg p-6 hover:shadow-md transition-shadow transform hover:-translate-y-0.5 bg-white">
                            <h3 class="font-semibold text-lg mb-2">
                                <a href="{{ route('lecturer.attendance.unit.view', $unit) }}"
                                   class="text-blue-600 hover:text-blue-800 hover:underline transition-colors duration-200">
                                    {{ $unit->name }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-600 mb-1">Code: {{ $unit->code }}</p>
                            <p class="text-sm text-gray-600 mb-1">Course: {{ $unit->course->name }}</p>
                            <p class="text-sm text-gray-600 mb-2">Level: {{ $unit->level->name }}</p>
                            <div class="mt-3">
                                <span class="text-xs font-medium px-3 py-1 rounded-full bg-blue-100 text-blue-800 shadow-sm">
                                    Total Records: {{ $unit->attendances->count() }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h3 class="text-xl font-semibold mb-4">Recent Attendance Records</h3>
                <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentAttendances as $attendance)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $attendance->student->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $attendance->unit->code }} - {{ $attendance->unit->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $attendance->marked_at->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize
                                        {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' :
                                           ($attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' :
                                           'bg-red-100 text-red-800') }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        No recent attendance records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection