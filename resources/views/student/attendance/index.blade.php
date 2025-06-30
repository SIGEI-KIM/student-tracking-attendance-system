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

                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Units for Today ({{ $today }})</h3>

                    {{-- Session Messages (Success, Error, Info, Validation Errors) --}}
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
                    @if (session('info'))
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <strong class="font-bold">Validation Error!</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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
                                                        {{-- CHANGED: Now using Alpine.js to open a modal instead of direct form submission --}}
                                                        <button type="button"
                                                                x-data="{}" {{-- Local x-data for this button's Alpine scope --}}
                                                                @click="$dispatch('open-attendance-modal', { unitId: {{ $unit->id }}, unitName: '{{ $unit->name }}' })"
                                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                            Mark Present
                                                        </button>
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

    {{-- START OF ALPINE.JS MODAL FOR ATTENDANCE CODE INPUT --}}
    <div x-data="{ showModal: false, selectedUnitId: null, selectedUnitName: '' }"
         x-on:open-attendance-modal.window="showModal = true; selectedUnitId = $event.detail.unitId; selectedUnitName = $event.detail.unitName; $nextTick(() => $refs.attendanceCodeInput.focus())"
         x-on:close-attendance-modal.window="showModal = false; selectedUnitId = null; selectedUnitName = ''"
         x-cloak> {{-- x-cloak hides element until Alpine is initialized --}}

        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50"></div>

        <div x-show="showModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed inset-0 z-50 overflow-y-auto"
             @click.away="showModal = false" {{-- Closes modal when clicking outside --}}
             @keydown.escape.window="showModal = false"> {{-- Closes modal with ESC key --}}
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
                    <div>
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.174 3.35 1.94 3.35h14.72c1.766 0 2.806-1.85 1.94-3.35L12 2.25 2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Mark Attendance for <span x-text="selectedUnitName"></span></h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Please enter the 6-digit attendance code provided by your lecturer.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Attendance Code Form inside the modal --}}
                    <form method="POST" action="{{ route('student.attendance.markByCode') }}" class="mt-5 sm:mt-6 space-y-4">
                        @csrf
                        <input type="hidden" name="unit_id" x-model="selectedUnitId">

                        <div>
                            <label for="attendance_code" class="sr-only">Attendance Code</label>
                            <input type="text"
                                   id="attendance_code"
                                   name="code" {{-- This is the name expected by $request->code --}}
                                   x-ref="attendanceCodeInput" {{-- Used by $nextTick to focus input --}}
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-center text-4xl font-extrabold tracking-widest uppercase"
                                   inputmode="numeric"
                                   pattern="[A-Za-z0-9]{6}"
                                   maxlength="6"
                                   required
                                   autocomplete="off"
                                   placeholder="______">
                            {{-- Display validation errors specifically for the code input if any --}}
                            @error('code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                             {{-- Display validation errors specifically for the unit_id input if any --}}
                            @error('unit_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-5 sm:mt-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Submit Code
                            </button>
                            <button type="button" @click="showModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- END OF ALPINE.JS MODAL --}}

</x-student-layout>