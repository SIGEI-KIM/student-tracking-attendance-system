<x-student-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-4xl text-gray-800 leading-tight border-b-4 border-indigo-600 pb-4 mb-4">
            {{ __('Mark Daily Attendance') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">

                    {{-- HERE IS THE CHANGE --}}
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Units for Today ({{ $today }})</h3>
                    {{-- END OF CHANGE --}}

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @isset($message)
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-5 rounded-md shadow-sm" role="alert">
                            <p class="font-bold text-lg mb-1">Information:</p>
                            <p class="text-md">{{ $message }}</p>
                        </div>
                    @else
                        @if ($units->isEmpty())
                            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-5 rounded-md shadow-sm" role="alert">
                                <p class="font-bold text-lg mb-1">No Units Scheduled</p>
                                <p class="text-md">No units are scheduled for your current academic level and semester today.</p>
                            </div>
                        @else
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
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Action</th>
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
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                    @php
                                                        $status = $attendancesToday->get($unit->id, 'pending'); // Default to 'pending' if not found
                                                        $statusClass = [
                                                            'present' => 'bg-green-100 text-green-800',
                                                            'absent' => 'bg-red-100 text-red-800', // Will be set by cron job
                                                            'pending' => 'bg-yellow-100 text-yellow-800', // For unmarked today
                                                        ];
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if ($status === 'pending')
                                                        <form action="{{ route('student.attendance.mark', $unit) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                Mark Present
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-gray-500">Already Marked</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endisset
                </div>
            </div>
        </div>
    </div>
</x-student-layout>