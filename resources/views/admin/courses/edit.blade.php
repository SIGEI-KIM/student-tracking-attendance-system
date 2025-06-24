@extends('layouts.admin') {{-- Make sure this matches your admin layout file --}}

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">Edit Course: {{ $course->name }}</h2>

                @if ($errors->any())
                    <div class="mb-4 p-4 text-red-700 bg-red-100 border border-red-400 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- Or @method('PATCH') --}}

                    {{-- Course Type --}}
                    <div class="mb-4">
                        <label for="course_type" class="block text-sm font-medium text-gray-700">Course Type</label>
                        <select name="course_type" id="course_type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            <option value="">-- Select Course Type --</option>
                            <option value="Degree" {{ old('course_type', $course->course_type) == 'Degree' ? 'selected' : '' }}>Degree</option>
                            <option value="Diploma" {{ old('course_type', $course->course_type) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                            <option value="Certificate" {{ old('course_type', $course->course_type) == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                            <option value="Masters" {{ old('course_type', $course->course_type) == 'Masters' ? 'selected' : '' }}>Masters</option>
                            <option value="PhD" {{ old('course_type', $course->course_type) == 'PhD' ? 'selected' : '' }}>PhD</option>
                        </select>
                        @error('course_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Course Name --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Course Name</label>
                        <input type="text" name="name" id="name"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               value="{{ old('name', $course->name) }}" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- REMOVED Abbreviation input field --}}

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">
                            Update Course
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="ml-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection