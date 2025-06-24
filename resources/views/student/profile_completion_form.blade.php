<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Complete Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto px-4 py-8 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Complete Your Student Profile</h2>
            <p class="text-gray-600 mb-6 text-center">Please fill out your profile details to proceed to course enrollment.</p>

            @if (session('warning'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('warning') }}</span>
                </div>
            @endif
            @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">There were some problems with your input.</span>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('student.profile.save') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="full_name" class="block text-gray-700 text-sm font-bold mb-2">Full Name:</label>
                    <input type="text" name="full_name" id="full_name"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('full_name') border-red-500 @enderror"
                           value="{{ old('full_name', Auth::user()->full_name) }}" required>
                    @error('full_name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="registration_number" class="block text-gray-700 text-sm font-bold mb-2">Registration Number:</label>
                    <input type="text" name="registration_number" id="registration_number"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('registration_number') border-red-500 @enderror"
                           value="{{ old('registration_number', Auth::user()->registration_number) }}" required>
                    @error('registration_number')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="school_email" class="block text-gray-700 text-sm font-bold mb-2">School Email:</label>
                    <input type="email" name="school_email" id="school_email"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           value="{{ Auth::user()->email }}" disabled> {{-- Display current user's email --}}
                    <p class="text-gray-500 text-xs mt-1">This is your registered email and cannot be changed here.</p>
                </div>

                <div class="mb-4">
                    <label for="id_number" class="block text-gray-700 text-sm font-bold mb-2">ID Number:</label>
                    <input type="text" name="id_number" id="id_number"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('id_number') border-red-500 @enderror"
                           value="{{ old('id_number', Auth::user()->id_number) }}" required>
                    @error('id_number')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="gender" class="block text-gray-700 text-sm font-bold mb-2">Gender:</label>
                    <select name="gender" id="gender"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('gender') border-red-500 @enderror" required>
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender', Auth::user()->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', Auth::user()->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', Auth::user()->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Complete Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>