{{-- resources/views/student/enroll.blade.php --}}

<x-student-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-800 leading-tight border-b-2 border-indigo-500 pb-2">
            Enroll in a Course
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">

                    {{-- Session Messages --}}
                    <div class="space-y-4 mb-6">
                        @if (session('success'))
                            <div class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                                <strong class="font-bold">Success!</strong>
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                                <strong class="font-bold">Error!</strong>
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif
                        @if (session('info'))
                            <div class="bg-blue-50 border border-blue-400 text-blue-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                                <strong class="font-bold">Info!</strong>
                                <span class="block sm:inline">{{ session('info') }}</span>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('student.enroll.store') }}">
                        @csrf

                        {{-- Course Selection --}}
                        <div class="mb-4">
                            <label for="course_id" class="block text-gray-700 text-sm font-bold mb-2">
                                Select Course:
                            </label>
                            <select name="course_id" id="course_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Choose a Course --</option>
                                @foreach($availableCourses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }} ({{ $course->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- REMOVED: Academic Level Selection --}}
                        {{-- The level will now be assigned automatically to Year 1, Semester 1 --}}

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150 ease-in-out">
                                Enroll
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-student-layout>