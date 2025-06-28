{{-- resources/views/admin/units/create.blade.php --}}
@extends('layouts.admin') {{-- Use your specific admin layout --}}

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Create New Unit</h2>

                    @if ($errors->any())
                        <div class="mb-4 p-4 text-red-700 bg-red-100 border border-red-400 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.units.store') }}" method="POST">
                        @csrf

                        {{-- Unit Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Unit Name</label>
                            <input type="text" name="name" id="name"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Unit Code --}}
                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-gray-700">Unit Code</label>
                            <input type="text" name="code" id="code"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   value="{{ old('code') }}" required>
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Course --}}
                        <div class="mb-4">
                            <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                            <select name="course_id" id="course_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Select Course --</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }} ({{ $course->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Level --}}
                        <div class="mb-4">
                            <label for="level_id" class="block text-sm font-medium text-gray-700">Level</label>
                            <select name="level_id" id="level_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Select Level --</option>
                                @foreach ($levels as $level)
                                    <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                        {{ $level->name }} ({{ $level->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('level_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Semester --}}
                        <div class="mb-4">
                            <label for="semester_id" class="block text-sm font-medium text-gray-700">Semester</label>
                            <select name="semester_id" id="semester_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Select Semester --</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->name }} (Year {{ $semester->year_number }})
                                    </option>
                                @endforeach
                            </select>
                            @error('semester_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Assign Lecturers --}}
                        <div class="mb-4">
                            <label for="lecturers" class="block text-sm font-medium text-gray-700">Assign Lecturers</label>
                            <select name="lecturers[]" id="lecturers" multiple
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @foreach ($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}" {{ in_array($lecturer->id, old('lecturers', [])) ? 'selected' : '' }}>
                                        {{ $lecturer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lecturers')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @error('lecturers.*')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <hr class="my-6 border-gray-200">

                        {{-- Unit Schedules Section --}}
                        <h3 class="text-xl font-bold mb-4 text-gray-800">Unit Schedules</h3>
                        <div id="schedule-container">
                            {{-- Initial schedule entry or old input --}}
                            @if(old('schedules'))
                                @foreach(old('schedules') as $key => $oldSchedule)
                                    @include('admin.units._schedule_fields', ['index' => $key, 'oldSchedule' => $oldSchedule, 'daysOfWeek' => $daysOfWeek])
                                @endforeach
                            @else
                                @include('admin.units._schedule_fields', ['index' => 0, 'daysOfWeek' => $daysOfWeek])
                            @endif
                        </div>
                        <button type="button" id="add-schedule" class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-md shadow-md focus:outline-none focus:ring transition duration-150 ease-in-out">
                            Add Another Schedule
                        </button>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                Create Unit
                            </button>
                            <a href="{{ route('admin.units.index') }}" class="ml-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    let scheduleIndex = {{ old('schedules') ? count(old('schedules')) : 1 }}; // Start index for new schedules

    document.getElementById('add-schedule').addEventListener('click', function () {
        const container = document.getElementById('schedule-container');
        const newScheduleHtml = `
            <div class="schedule-entry border border-gray-300 p-4 mb-4 rounded-md bg-gray-50 relative">
                <button type="button" class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-xl font-bold remove-schedule" title="Remove Schedule">&times;</button>
                <div class="mb-4">
                    <label for="schedules_${scheduleIndex}_day_of_week_numeric" class="block text-sm font-medium text-gray-700">Day of Week</label>
                    <select name="schedules[${scheduleIndex}][day_of_week_numeric]" id="schedules_${scheduleIndex}_day_of_week_numeric" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        <option value="">-- Select Day --</option>
                        @foreach($daysOfWeek as $numeric => $dayName)
                            <option value="{{ $numeric }}">{{ $dayName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="schedules_${scheduleIndex}_start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input type="time" name="schedules[${scheduleIndex}][start_time]" id="schedules_${scheduleIndex}_start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
                <div class="mb-0"> {{-- Use mb-0 for the last element in the group --}}
                    <label for="schedules_${scheduleIndex}_end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                    <input type="time" name="schedules[${scheduleIndex}][end_time]" id="schedules_${scheduleIndex}_end_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newScheduleHtml);
        scheduleIndex++;
    });

    // Event delegation for remove buttons
    document.getElementById('schedule-container').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-schedule')) {
            const entryToRemove = event.target.closest('.schedule-entry');
            if (entryToRemove) {
                // Ensure at least one schedule entry remains
                if (document.querySelectorAll('.schedule-entry').length > 1) {
                    entryToRemove.remove();
                } else {
                    alert("You must have at least one schedule entry for the unit.");
                }
            }
        }
    });
});
</script>
@endpush