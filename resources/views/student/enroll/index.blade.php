<x-student-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-800 leading-tight border-b-2 border-indigo-500 pb-2">
            📚 Enroll in a Course
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50"> {{-- Added a subtle background color for the page body --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"> {{-- Stronger shadow for the main container --}}
                <div class="p-8 bg-white border-b border-gray-200"> {{-- Increased padding slightly --}}

                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3 border-indigo-200 flex items-center">
                        <svg class="w-7 h-7 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"></path></svg>
                        Select Your Course and Level
                    </h2>

                    {{-- Session Messages Container --}}
                    <div class="space-y-4 mb-6"> {{-- Added space-y for consistent message spacing --}}
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
                        @if (session('warning'))
                            <div class="bg-yellow-50 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                                <strong class="font-bold">Warning!</strong>
                                <span class="block sm:inline">{{ session('warning') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Enrollment Form Section --}}
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 mb-8 transform transition duration-300 hover:shadow-xl hover:scale-[1.005]">
                        <form action="{{ route('student.enroll.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="course_id" :value="__('Select Course')" class="text-gray-700 font-semibold mb-2" />
                                <select id="course_id" name="course_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 py-2 px-3 text-gray-800" required>
                                    <option value="">-- Please choose a course --</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }} ({{ $course->code }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('course_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="level_id" :value="__('Select Level (e.g., Year 1, Semester 1)')" class="text-gray-700 font-semibold mb-2" />
                                <select id="level_id" name="level_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 py-2 px-3 text-gray-800" required>
                                    <option value="">-- Please choose your study level --</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('level_id')" class="mt-2" />
                            </div>

                            {{-- If you have a separate 'semester' concept, you could add another select here --}}
                            {{--
                            <div>
                                <x-input-label for="semester" :value="__('Select Semester')" class="text-gray-700 font-semibold mb-2" />
                                <select id="semester" name="semester" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 py-2 px-3 text-gray-800" required>
                                    <option value="">-- Choose Semester --</option>
                                    <option value="Semester 1" {{ old('semester') == 'Semester 1' ? 'selected' : '' }}>Semester 1</option>
                                    <option value="Semester 2" {{ old('semester') == 'Semester 2' ? 'selected' : '' }}>Semester 2</option>
                                </select>
                                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                            </div>
                            --}}

                            <div class="flex items-center justify-end pt-4 border-t border-gray-100"> {{-- Added top border for separation --}}
                                <x-primary-button class="ms-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('Enroll Now') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    {{-- Your Currently Enrolled Courses Section --}}
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                        <h3 class="text-2xl font-bold text-gray-800 mt-0 mb-5 border-b pb-3 border-indigo-200 flex items-center">
                            <svg class="w-7 h-7 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Your Currently Enrolled Courses
                        </h3>
                        @if (Auth::user()->courses->isEmpty())
                            <div class="bg-blue-50 border border-blue-400 text-blue-700 px-6 py-5 rounded-md relative shadow-sm text-center">
                                <p class="text-lg font-medium">You have not enrolled in any courses yet.</p>
                                <p class="text-sm italic text-gray-600 mt-2">Use the form above to enroll!</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach(Auth::user()->courses as $course)
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg shadow-md p-5 transform transition duration-300 hover:shadow-lg hover:-translate-y-1 relative overflow-hidden">
                                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-teal-500 to-green-500 rounded-t-lg"></div> {{-- Different color stripe for enrolled courses --}}
                                        <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $course->name }}</h4>
                                        <p class="text-gray-600 text-sm mb-3">Code: <span class="font-semibold text-gray-700">{{ $course->code }}</span></p>
                                        @if($course->pivot->level_id)
                                            <p class="text-gray-700 text-sm">Level: <span class="font-semibold text-blue-700">{{ \App\Models\Level::find($course->pivot->level_id)->name ?? 'N/A' }}</span></p>
                                        @else
                                            <p class="text-gray-500 text-sm italic">No level assigned.</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-student-layout>