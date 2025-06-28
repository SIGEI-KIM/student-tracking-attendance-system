{{-- resources/views/admin/units/_schedule_fields.blade.php --}}
{{--
    This partial is used to render a single set of schedule input fields.
    It expects:
    - $index: the numeric index for array names (e.g., schedules[0][day_of_week_numeric])
    - $daysOfWeek: an associative array of day numbers to names (1 => 'Monday', etc.)
    - $oldSchedule (optional): an array containing old input values for this specific schedule entry (used in 'create' on validation error)
    - $schedule (optional): a Schedule model instance (used in 'edit' for existing schedules)
--}}
<div class="schedule-entry border border-gray-300 p-4 mb-4 rounded-md bg-gray-50 relative">
    <button type="button" class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-xl font-bold remove-schedule" title="Remove Schedule">&times;</button>
    <div class="mb-4">
        <label for="schedules_{{ $index }}_day_of_week_numeric" class="block text-sm font-medium text-gray-700">Day of Week</label>
        <select name="schedules[{{ $index }}][day_of_week_numeric]" id="schedules_{{ $index }}_day_of_week_numeric"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            <option value="">-- Select Day --</option>
            @foreach($daysOfWeek as $numeric => $dayName)
                <option value="{{ $numeric }}"
                    {{ (isset($oldSchedule) && old('schedules.'.$index.'.day_of_week_numeric', $oldSchedule['day_of_week_numeric']) == $numeric) || (isset($schedule) && $schedule->day_of_week_numeric == $numeric) ? 'selected' : '' }}>
                    {{ $dayName }}
                </option>
            @endforeach
        </select>
        @error('schedules.'.$index.'.day_of_week_numeric')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-4">
        <label for="schedules_{{ $index }}_start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
        <input type="time" name="schedules[{{ $index }}][start_time]" id="schedules_{{ $index }}_start_time"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
               value="{{ isset($oldSchedule) ? old('schedules.'.$index.'.start_time', $oldSchedule['start_time']) : (isset($schedule) ? $schedule->start_time->format('H:i') : '') }}" required>
        @error('schedules.'.$index.'.start_time')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="mb-0"> {{-- Use mb-0 for the last element in the group --}}
        <label for="schedules_{{ $index }}_end_time" class="block text-sm font-medium text-gray-700">End Time</label>
        <input type="time" name="schedules[{{ $index }}][end_time]" id="schedules_{{ $index }}_end_time"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
               value="{{ isset($oldSchedule) ? old('schedules.'.$index.'.end_time', $oldSchedule['end_time']) : (isset($schedule) ? $schedule->end_time->format('H:i') : '') }}" required>
        @error('schedules.'.$index.'.end_time')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>