@extends('layouts.admin')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Create New Level</h2>

                    @if ($errors->any())
                        <div class="mb-4 p-4 text-red-700 bg-red-100 border border-red-400 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.levels.store') }}" method="POST">
                        @csrf

                        {{-- Changed to Year input --}}
                        <div class="mb-4">
                            <label for="year_number" class="block text-sm font-medium text-gray-700">Year</label>
                            <input type="number" name="year_number" id="year_number"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   value="{{ old('year_number') }}" required min="1">
                            @error('year_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Changed to Semester dropdown with Semester 3 option --}}
                        <div class="mb-4">
                            <label for="semester_number" class="block text-sm font-medium text-gray-700">Semester</label>
                            <select name="semester_number" id="semester_number"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">-- Select Semester --</option>
                                <option value="1" {{ old('semester_number') == '1' ? 'selected' : '' }}>Semester 1</option>
                                <option value="2" {{ old('semester_number') == '2' ? 'selected' : '' }}>Semester 2</option>
                                <option value="3" {{ old('semester_number') == '3' ? 'selected' : '' }}>Semester 3</option> {{-- Added Semester 3 --}}
                            </select>
                            @error('semester_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Hidden inputs for 'name' and 'code' fields --}}
                        {{-- These will be populated by JavaScript before form submission --}}
                        <input type="hidden" name="name" id="hidden_name" value="">
                        <input type="hidden" name="code" id="hidden_code" value="">

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                                Create Level
                            </button>
                            <a href="{{ route('admin.levels.index') }}" class="ml-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const yearInput = document.getElementById('year_number');
            const semesterSelect = document.getElementById('semester_number');
            const hiddenNameInput = document.getElementById('hidden_name');
            const hiddenCodeInput = document.getElementById('hidden_code');

            function updateHiddenFields() {
                const year = yearInput.value;
                const semester = semesterSelect.value;

                if (year && semester) {
                    hiddenNameInput.value = `Year ${year} Semester ${semester}`;
                    hiddenCodeInput.value = `Y${year}S${semester}`;
                } else {
                    hiddenNameInput.value = '';
                    hiddenCodeInput.value = '';
                }
            }

            // Update on input/change
            yearInput.addEventListener('input', updateHiddenFields);
            semesterSelect.addEventListener('change', updateHiddenFields);

            // Initial update in case of old input
            updateHiddenFields();
        });
    </script>
@endsection