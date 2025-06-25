<x-student-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-3xl text-gray-800 leading-tight border-b-2 border-indigo-500 pb-2">
            🚀 Student Dashboard
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">

                    {{-- Session Messages Container --}}
                    <div class="space-y-4 mb-6">
                        @if (session('success'))
                            <div class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                                <strong class="font-bold">Success!</strong>
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif
                        @if (session('info'))
                            <div class="bg-blue-50 border border-blue-400 text-blue-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                                <strong class="font-bold">Info:</strong>
                                <span class="block sm:inline">{{ session('info') }}</span>
                            </div>
                        @endif
                        @if (session('warning'))
                            <div class="bg-yellow-50 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                                <strong class="font-bold">Warning!</strong>
                                <span class="block sm:inline">{{ session('warning') }}</span>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                                <strong class="font-bold">Error!</strong>
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Student Profile Details Section --}}
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200 mb-8 transform transition duration-300 hover:shadow-xl hover:scale-[1.005]">
                        <h3 class="text-2xl font-bold text-gray-800 mb-5 border-b pb-3 border-indigo-200 flex items-center">
                            <svg class="w-7 h-7 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Your Profile Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 text-lg">
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">Full Name:</span>
                                {{-- CORRECTED: Access full_name through the student relationship --}}
                                <span class="text-gray-900 font-semibold">{{ Auth::user()->student->full_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">Reg. Number:</span>
                                {{-- CORRECTED: Access registration_number through the student relationship --}}
                                <span class="text-gray-900 font-semibold">{{ Auth::user()->student->registration_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">School Email:</span>
                                <span class="text-indigo-700 font-semibold truncate">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">ID Number:</span>
                                {{-- CORRECTED: Access id_number through the student relationship --}}
                                <span class="text-gray-900 font-semibold">{{ Auth::user()->student->id_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">Gender:</span>
                                {{-- CORRECTED: Access gender through the student relationship --}}
                                <span class="text-gray-900 font-semibold">{{ Auth::user()->student->gender ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">Profile Status:</span>
                                {{-- This is correctly checking profile_completed on the User model, assuming you've also fixed the StudentProfileController to update it. --}}
                                @if(Auth::user()->profile_completed)
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-green-200 text-green-900 shadow-sm">Complete ✅</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-red-200 text-red-900 shadow-sm">Incomplete ❌</span>
                                @endif
                            </div>

                            {{-- NEW: Display Enrolled Course in Profile Details --}}
                            {{-- This section relies on $primaryEnrolledCourse being passed from the controller,
                                 which likely comes from Auth::user()->student->courses->first() or similar.
                                 If it's working, keep it as is. --}}
                            @if($primaryEnrolledCourse)
                                <div class="flex items-center">
                                    <span class="text-gray-600 font-medium w-36 shrink-0">Enrolled Course:</span>
                                    <span class="text-gray-900 font-semibold">
                                        {{ $primaryEnrolledCourse->name }}
                                        @if($primaryEnrolledCourse->level)
                                            ({{ $primaryEnrolledCourse->level->name }})
                                        @endif
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center col-span-full">
                                    <span class="text-red-500 italic">No primary course selected yet.</span>
                                </div>
                            @endif
                        </div>

                        {{-- This section's conditional is good, checking Auth::user()->profile_completed --}}
                        @if(!Auth::user()->profile_completed)
                             <div class="col-span-full mt-8 pt-6 border-t border-gray-100 text-center">
                                 <p class="text-red-600 text-lg mb-4 font-medium">
                                     Your profile is incomplete. Please complete it to access all features, including course enrollment.
                                 </p>
                                 <a href="{{ route('student.profile.complete') }}" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                                     Complete Your Profile Now
                                 </a>
                             </div>
                        @endif
                    </div>

                    {{-- Enrolled Courses / Enrollment Prompt Section (This remains as is for showing all courses or prompt) --}}
                    <div class="bg-white p-6 rounded-lg shadow-lg border border-gray-200">
                        @if(empty($enrolledCourses) || $enrolledCourses->isEmpty())
                            <div class="bg-blue-50 border border-blue-400 text-blue-700 px-6 py-5 rounded-md relative shadow-sm text-center">
                                <p class="text-lg mb-3">
                                    <strong class="font-bold">You are not enrolled in any course yet.</strong>
                                </p>
                                @if(!Auth::user()->profile_completed)
                                    <p class="text-md italic text-gray-700">Please <span class="font-bold text-red-600">complete your profile first</span> to enable course enrollment.</p>
                                @else
                                    <p class="text-md mb-4">Click below to start your academic journey!</p>
                                    <a href="{{ route('student.enroll.index') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300 ease-in-out transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Enroll in a Course Now
                                    </a>
                                @endif
                            </div>
                        @else
                            <h3 class="text-2xl font-bold text-gray-800 mb-5 border-b pb-3 border-indigo-200 flex items-center">
                                <svg class="w-7 h-7 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Your Enrolled Courses
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($enrolledCourses as $course)
                                    <div class="bg-white border border-gray-200 rounded-lg shadow-md p-6 transform transition duration-300 hover:shadow-lg hover:-translate-y-1 relative overflow-hidden">
                                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-t-lg"></div>
                                        <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $course->name }}</h4>
                                        <p class="text-gray-600 text-sm mb-4">Course Code: <span class="font-semibold text-gray-700">{{ $course->code }}</span></p>

                                        @if($course->level)
                                            <div class="mb-4">
                                                <h5 class="font-semibold text-gray-700 mb-2">Level: <span class="text-blue-700">{{ $course->level->name }}</span></h5>
                                                <a href="{{ route('student.view-enrolled-units', ['course' => $course->id, 'level' => $course->level->id]) }}"
                                                   class="inline-flex items-center text-indigo-600 hover:text-indigo-800 text-sm font-medium transition duration-150 ease-in-out">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                    View Units
                                                </a>
                                            </div>
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