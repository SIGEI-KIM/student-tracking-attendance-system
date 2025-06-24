{{-- resources/views/student/mark_attendance.blade.php --}}

<x-student-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-800 leading-tight border-b-2 border-indigo-500 pb-2">
            Mark Attendance
        </h2>
    </x-slot>

    {{-- The content that will be rendered inside the `{{ $slot }}` of student-layout --}}
    <div class="py-1">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-6">Mark Attendance for {{ $unit->name }}</h2>

                    <div class="mb-6">
                        <p class="text-gray-600">Today's Date: {{ now()->format('l, F j, Y') }}</p>
                    </div>

                    <div id="attendance-response" class="hidden p-4 rounded"></div>

                    <div class="flex space-x-4">
                        <button onclick="markAttendance('present')" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Present
                        </button>
                        <button onclick="markAttendance('late')" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Late
                        </button>
                        <button onclick="markAttendance('absent')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Absent
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Your JavaScript markAttendance function here
        async function markAttendance(status) {
            const unitId = {{ $unit->id }}; // Get unit ID from Blade
            const responseDiv = document.getElementById('attendance-response');
            responseDiv.classList.add('hidden'); // Hide previous messages

            try {
                const response = await fetch('/api/attendances/mark', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        unit_id: unitId,
                        status: status
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    responseDiv.classList.remove('hidden', 'bg-red-100', 'text-red-700');
                    responseDiv.classList.add('bg-green-100', 'text-green-700');
                    responseDiv.textContent = data.message;
                    // Optionally disable buttons after successful marking
                    document.querySelectorAll('.flex.space-x-4 button').forEach(button => button.disabled = true);
                } else {
                    responseDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700');
                    responseDiv.classList.add('bg-red-100', 'text-red-700');
                    responseDiv.textContent = data.error || 'An error occurred.';
                }
            } catch (error) {
                console.error('Error marking attendance:', error);
                responseDiv.classList.remove('hidden', 'bg-green-100', 'text-green-700');
                responseDiv.classList.add('bg-red-100', 'text-red-700');
                responseDiv.textContent = 'Network error or server unreachable.';
            }
        }
    </script>
</x-student-layout>