
<x-student-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-800 leading-tight border-b-2 border-indigo-500 pb-2">
            📚 Manage Academic Levels
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    </div>

                    @if($enrolledCourses->isEmpty())
                        <div class="bg-blue-50 border border-blue-400 text-blue-700 px-6 py-5 rounded-md relative shadow-sm text-center">
                            <p class="text-lg mb-3">
                                <strong class="font-bold">You are not enrolled in any course yet.</strong>
                            </p>
                            <p class="text-md italic text-gray-700">Please enroll in a course to manage academic levels.</p>
                        </div>
                    @else
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3 border-indigo-200 flex items-center">
                            Select Level for Your Courses
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($enrolledCourses as $course)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6 relative overflow-hidden">
                                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-t-lg"></div>
                                    <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $course->name }}</h4>
                                    <p class="text-gray-600 text-sm mb-4">Course Code: <span class="font-semibold text-gray-700">{{ $course->code ?? 'N/A' }}</span></p>

                                    <form action="{{ route('student.update-level', ['course' => $course->id]) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="level_{{ $course->id }}" class="block text-gray-700 text-sm font-bold mb-2">
                                                Current Level:
                                                <span class="text-blue-700">
                                                    @if($course->pivot->level_id)
                                                        {{ \App\Models\Level::find($course->pivot->level_id)->name ?? 'N/A' }}
                                                    @else
                                                        N/A (Please select)
                                                    @endif
                                                </span>
                                            </label>
                                            <select name="level_id" id="level_{{ $course->id }}" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="">Select a Level</option>
                                                @foreach($availableLevels as $level)
                                                    <option value="{{ $level->id }}" {{ ($course->pivot->level_id == $level->id) ? 'selected' : '' }}>
                                                        {{ $level->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('level_id')
                                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150 ease-in-out">
                                            Update Level
                                        </button>
                                    </form>

                                    {{-- NEW/MODIFIED: Conditionally show the "View Units" link --}}
                                    @if($course->pivot->level_id)
                                        <a href="{{ route('student.view-enrolled-units', ['course' => $course->id, 'level' => $course->pivot->level_id]) }}"
                                           class="mt-4 inline-flex items-center text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150 ease-in-out">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            View Units (Current Level)
                                        </a>
                                    @else
                                        <p class="mt-4 text-sm text-gray-500 italic">Please select a level to view units.</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-student-layout>