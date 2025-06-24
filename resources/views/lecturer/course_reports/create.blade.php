@extends('layouts.lecturer')

@section('content')
<div class="py-6 sm:py-8 md:py-10 lg:py-12">
    <div class="max-w-xl sm:max-w-3xl md:max-w-5xl lg:max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-4 sm:p-6 lg:p-8 bg-white border-b border-gray-200">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 sm:mb-6 flex items-center">
                    <svg class="h-7 w-7 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0h.01M12 3v2.25A2.25 2.25 0 0014.25 7h2.25M21 12v7.5A2.25 2.25 0 0118.75 22H5.25A2.25 2.25 0 013 19.5V4.5A2.25 2.25 0 015.25 2.25H9M13.5 12a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"></path></svg>
                    Submit Course Report
                </h2>

                {{-- Session Messages Container --}}
                <div class="space-y-3 sm:space-y-4 mb-5 sm:mb-6">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-500 text-green-800 px-4 py-3 rounded-lg relative shadow-md" role="alert">
                            <strong class="font-semibold">Success!</strong>
                            <span class="block sm:inline text-sm sm:text-base">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-500 text-red-800 px-4 py-3 rounded-lg relative shadow-md" role="alert">
                            <strong class="font-semibold">Whoops!</strong>
                            <span class="block sm:inline">There were some problems with your submission.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="bg-gray-50 p-5 sm:p-6 rounded-xl shadow-inner border border-gray-100">
                    <form action="{{ route('lecturer.report_submission.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Course Selection --}}
                        <div class="mb-4">
                            <label for="course_id" class="block text-sm font-medium text-gray-700 mb-1">Course:</label>
                            <select id="course_id" name="course_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Unit Selection (Now Required) --}}
                        <div class="mb-4">
                            <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">Unit:</label> {{-- REMOVED "(Optional)" --}}
                            <select id="unit_id" name="unit_id" required {{-- ADDED 'required' --}}
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                                <option value="">Select Unit</option> {{-- KEPT THIS AS DEFAULT PROMPT, BUT NOW REQUIRED --}}
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} ({{ $unit->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- PDF File Upload --}}
                        <div class="mb-4">
                            <label for="report_file" class="block text-sm font-medium text-gray-700 mb-1">Attach PDF Report:</label>
                            <input type="file" id="report_file" name="report_file" accept=".pdf" required
                                class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-md file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100"/>
                            @error('report_file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Max file size: 10MB. Only PDF files are allowed.</p>
                        </div>

                        {{-- Remarks --}}
                        <div class="mb-6">
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Remarks (Optional):</label>
                            <textarea id="remarks" name="remarks" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Any additional remarks or comments about the report...">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-5 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Submit Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection