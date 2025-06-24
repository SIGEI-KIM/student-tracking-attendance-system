@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8"> {{-- Adjusted max-w to be a bit narrower for forms --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"> {{-- Consistent shadow and rounded corners --}}
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-3xl font-extrabold mb-8 text-gray-800 border-b-2 border-indigo-500 pb-2">Register New Lecturer</h2> {{-- Consistent title styling --}}
                
                <form method="POST" action="{{ route('admin.lecturers.store') }}">
                    @csrf

                    <div class="mb-5"> {{-- Consistent vertical spacing --}}
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="name"> {{-- Bolder label --}}
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      @error('name') border-red-500 @enderror"> {{-- Added consistent input styling and error highlighting --}}
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input id="password" type="password" name="password" required
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="password_confirmation">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="staff_id">
                            Staff ID <span class="text-red-500">*</span>
                        </label>
                        <input id="staff_id" type="text" name="staff_id" value="{{ old('staff_id') }}" required
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      @error('staff_id') border-red-500 @enderror">
                        @error('staff_id')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="department">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <input id="department" type="text" name="department" value="{{ old('department') }}" required
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      @error('department') border-red-500 @enderror">
                        @error('department')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="faculty">
                            Faculty <span class="text-red-500">*</span>
                        </label>
                        <input id="faculty" type="text" name="faculty" value="{{ old('faculty') }}" required
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      @error('faculty') border-red-500 @enderror">
                        @error('faculty')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="specialization">
                            Specialization (Optional)
                        </label>
                        <input id="specialization" type="text" name="specialization" value="{{ old('specialization') }}"
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      @error('specialization') border-red-500 @enderror">
                        @error('specialization')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-6"> {{-- Added margin-top --}}
                        <button type="submit" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md 
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
                                       transition ease-in-out duration-150 shadow-md"> {{-- Styled button --}}
                            Register Lecturer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection