@extends('layouts.lecturer')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Generate Attendance Code') }}
    </h2>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 lg:p-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-6 text-center">
                Generate New Attendance Code
            </h1>

            <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-inner mb-8">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-5">Create New Code</h2>
                <form action="{{ route('lecturer.attendance.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Select Unit:
                        </label>
                        <select name="unit_id" id="unit_id" required
                                class="mt-1 block w-full px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base text-gray-900 dark:text-gray-100 transition duration-150 ease-in-out">
                            <option value="" class="dark:bg-gray-700">-- Select a Unit --</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" class="dark:bg-gray-700" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->code }} - {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Duration (minutes):
                        </label>
                        <input type="number" name="duration" id="duration" min="1" required value="{{ old('duration', 60) }}"
                               class="mt-1 block w-full px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base text-gray-900 dark:text-gray-100 transition duration-150 ease-in-out">
                        @error('duration')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                            Max Students:
                        </label>
                        <input type="number" name="capacity" id="capacity" min="1" required placeholder="e.g., 60" value="{{ old('capacity') }}"
                               class="mt-1 block w-full px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base text-gray-900 dark:text-gray-100 transition duration-150 ease-in-out">
                        @error('capacity')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150 transform hover:scale-105">
                            <svg class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Generate Code
                        </button>
                    </div>
                </form>
            </div>

            @if ($latestCode)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold text-blue-800 dark:text-blue-200 mb-4 flex items-center">
                        <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Latest Active Code:
                    </h2>
                    <div class="space-y-3 text-gray-700 dark:text-gray-300">
                        <p class="text-lg">
                            <strong>Code:</strong>
                            <span class="font-mono text-2xl text-blue-800 dark:text-blue-300 bg-blue-100 dark:bg-blue-900 px-3 py-1 rounded-md tracking-wider inline-block">
                                {{ $latestCode->code }}
                            </span>
                        </p>
                        <p><strong>Unit:</strong> {{ $latestCode->unit->code }} - {{ $latestCode->unit->name }}</p>

                        {{-- Timer Display - Updated structure --}}
                        <div id="countdown-container" class="p-2 rounded-md transition-colors duration-300">
                            <p class="text-lg flex items-center">
                                <strong class="mr-2">Time Remaining:</strong>
                                <span id="countdown-timer" class="font-semibold text-2xl">
                                    Calculating...
                                </span>
                            </p>
                        </div>

                        <p><strong>Expires At:</strong> <span class="text-gray-600 dark:text-gray-400 font-semibold">{{ $latestCode->expires_at->format('Y-m-d H:i:s') }}</span></p>

                        <p class="text-lg">
                            <strong>Students Marked Present:</strong>
                            <span class="font-semibold text-green-700 dark:text-green-300">{{ $latestCode->attendances_count }}</span>
                            / <span class="font-semibold text-blue-700 dark:text-blue-300">{{ $latestCode->capacity }}</span>
                        </p>
                    </div>

                    <form id="invalidate-form-{{ $latestCode->id }}" action="{{ route('lecturer.attendance.invalidate', $latestCode) }}" method="POST" class="mt-6">
                        @csrf
                        @method('PUT')
                        <button type="button"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition ease-in-out duration-150 transform hover:scale-105"
                                onclick="confirmInvalidate({{ $latestCode->id }});">
                            <svg class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Invalidate Code
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-8 p-6 text-center bg-gray-100 dark:bg-gray-700 rounded-lg shadow-sm">
                    <p class="text-gray-600 dark:text-gray-300 text-lg">No active attendance codes found for your units.</p>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes blink-opacity {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }

        /* NEW: Keyframe for background color change */
        @keyframes blink-background {
            0%, 100% { background-color: transparent; } /* Start/end transparent */
            50% { background-color: rgba(254, 226, 226, 0.8); } /* Light red for blink */
        }

        /* Dark mode variant for background */
        html.dark .blink-background-dark {
             0%, 100% { background-color: transparent; }
             50% { background-color: rgba(153, 27, 27, 0.4); } /* Darker red for dark mode */
        }

        /* Combined blinking class */
        .blink-alert {
            animation: blink-opacity 0.8s infinite;
        }

        /* For the container's background blink */
        .blink-container-bg {
            animation: blink-background 0.8s infinite;
        }

        /* NEW: Class for expired state */
        .expired-state {
            color: #9ca3af; /* Tailwind gray-400/500 */
            text-decoration: line-through;
            font-style: italic;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes}m ${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}s`;
        }

        function confirmInvalidate(codeId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to invalidate this attendance code. This action cannot be undone and will prevent further attendance entries for this code.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, invalidate it!',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('invalidate-form-' + codeId).submit();
                }
            })
        }

        @if ($latestCode)
            const expiresAtTimestamp = new Date("{{ $latestCode->expires_at->toIso8601String() }}").getTime();
            const countdownTimerElement = document.getElementById('countdown-timer');
            const countdownContainer = document.getElementById('countdown-container'); // NEW
            let countdownInterval;

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = expiresAtTimestamp - now;

                if (distance < 0) {
                    clearInterval(countdownInterval);
                    countdownTimerElement.innerHTML = 'EXPIRED!';
                    countdownTimerElement.classList.add('font-bold', 'expired-state'); // NEW class for expired text
                    countdownTimerElement.classList.remove('blink-alert', 'text-red-600', 'text-yellow-500'); // Clean up blinking classes

                    countdownContainer.classList.add('bg-gray-200', 'dark:bg-gray-700'); // Gray out background
                    countdownContainer.classList.remove('blink-container-bg', 'bg-red-100', 'bg-yellow-100', 'dark:bg-red-900', 'dark:bg-yellow-900'); // Remove blinking background
                    return;
                }

                const totalSeconds = Math.floor(distance / 1000);

                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;

                countdownTimerElement.innerHTML = `${minutes}m ${seconds < 10 ? '0' : ''}${seconds}s`;

                // Blinking logic for last 10 seconds
                if (totalSeconds <= 10 && totalSeconds > 0) {
                    countdownTimerElement.classList.add('blink-alert', 'text-red-600', 'dark:text-red-400');
                    countdownTimerElement.classList.remove('text-green-600', 'text-gray-700'); // Ensure no other text colors conflict

                    countdownContainer.classList.add('blink-container-bg'); // Apply background blink to container
                    countdownContainer.classList.add('bg-red-100', 'dark:bg-red-900'); // Ensure background color is red
                }
                // Optional: A warning state before the final 10 seconds (e.g., last 30 seconds)
                else if (totalSeconds <= 30 && totalSeconds > 10) {
                    countdownTimerElement.classList.remove('blink-alert', 'text-red-600', 'dark:text-red-400');
                    countdownTimerElement.classList.add('text-yellow-600', 'dark:text-yellow-400'); // Yellow text for warning

                    countdownContainer.classList.remove('blink-container-bg'); // Stop background blink if it was active
                    countdownContainer.classList.add('bg-yellow-50', 'dark:bg-yellow-900/30'); // Yellow background
                    countdownContainer.classList.remove('bg-red-100', 'dark:bg-red-900'); // Remove red background
                }
                else {
                    // Default state (more than 30 seconds remaining)
                    countdownTimerElement.classList.remove('blink-alert', 'text-red-600', 'dark:text-red-400', 'text-yellow-600', 'dark:text-yellow-400');
                    countdownTimerElement.classList.add('text-green-600', 'dark:text-green-400'); // Green text for normal time

                    countdownContainer.classList.remove('blink-container-bg', 'bg-red-100', 'dark:bg-red-900', 'bg-yellow-50', 'dark:bg-yellow-900/30'); // Remove any warning backgrounds
                    countdownContainer.classList.add('bg-gray-50', 'dark:bg-gray-700/30'); // Neutral background
                }
            }

            updateCountdown();
            countdownInterval = setInterval(updateCountdown, 1000);
        @endif
    </script>
    @endpush
@endsection