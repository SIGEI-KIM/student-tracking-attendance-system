<x-student-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mark Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6 text-center">Enter Attendance Code</h3>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Info!</strong>
                            <span class="block sm:inline">{{ session('info') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.attendance.submit_code') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="unit_id" :value="__('Select Unit')" />
                            <select id="unit_id" name="unit_id" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Select your Unit --</option>
                                @forelse ($enrolledUnits as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->code }})
                                    </option>
                                @empty
                                    <option value="" disabled>No units found</option>
                                @endforelse
                            </select>
                            <x-input-error :messages="$errors->get('unit_id')" class="mt-2" />
                            @if($enrolledUnits->isEmpty())
                                <p class="text-sm text-red-500 mt-2">You are not enrolled in any units. Please contact administration.</p>
                            @endif
                        </div>

                        <div>
                            <x-input-label for="attendance_code" :value="__('6-Digit Attendance Code')" />
                            <x-text-input id="attendance_code" name="attendance_code" type="text" class="mt-1 block w-full text-center text-4xl font-extrabold tracking-widest uppercase" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus />
                            <x-input-error :messages="$errors->get('attendance_code')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Mark Me Present') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-student-layout>