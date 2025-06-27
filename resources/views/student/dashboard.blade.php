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
                                <span class="text-gray-900 font-semibold">{{ Auth::user()->student->full_name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">Reg. Number:</span>
                                <span class="text-gray-900 font-semibold">{{ Auth::user()->student->registration_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">School Email:</span>
                                <span class="text-indigo-700 font-semibold truncate">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">ID Number:</span>
                                <span class="text-gray-900 font-semibold">{{ Auth::user()->student->id_number ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">Gender:</span>
                                <span class="text-gray-900 font-semibold">{{ Auth::user()->student->gender ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-gray-600 font-medium w-36 shrink-0">Profile Status:</span>
                                @if(Auth::user()->profile_completed)
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-green-200 text-green-900 shadow-sm">Complete ✅</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-red-200 text-red-900 shadow-sm">Incomplete ❌</span>
                                @endif
                            </div>

                            {{-- Display Primary Enrolled Course --}}
                            @if($primaryEnrolledCourse)
                                <div class="flex items-center">
                                    <span class="text-gray-600 font-medium w-36 shrink-0">Enrolled Course:</span>
                                    <span class="text-gray-900 font-semibold">
                                        {{ $primaryEnrolledCourse->name }}
                                        {{-- Check if the pivot level_id exists directly --}}
                                        @if($primaryEnrolledCourse->pivot->level_id)
                                            ({{ $primaryEnrolledCourse->level->name }})
                                        @else
                                            (Level pending admin assignment)
                                        @endif
                                    </span>
                                </div>
                            @else
                                <div class="flex items-center col-span-full">
                                    <span class="text-red-500 italic">You are not yet enrolled in a course.</span>
                                </div>
                            @endif
                        </div>

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
                </div>
            </div>
        </div>
    </div>
</x-student-layout>