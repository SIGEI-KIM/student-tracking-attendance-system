@extends('layouts.admin') {{-- Or your appropriate admin layout --}}

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-3xl font-extrabold mb-8 text-gray-800 border-b-2 border-indigo-500 pb-2">Create New User</h2>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label for="name" class="block text-gray-700 text-sm font-semibold mb-2">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                      @error('name') border-red-500 @enderror" required>
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">
                            Email: <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                      @error('email') border-red-500 @enderror" required>
                        @error('email')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">
                            Password: <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password"
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                      @error('password') border-red-500 @enderror" required>
                        @error('password')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-semibold mb-2">
                            Confirm Password: <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>

                    <div class="mb-6"> {{-- Increased margin-bottom for better spacing before buttons --}}
                        <label for="role" class="block text-gray-700 text-sm font-semibold mb-2">
                            Role: <span class="text-red-500">*</span>
                        </label>
                        <select name="role" id="role"
                                class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                       @error('role') border-red-500 @enderror">
                            <option value="">Select Role</option> {{-- Added a default 'Select Role' option --}}
                            @foreach($roles as $role)
                                <option value="{{ $role->value }}" {{ old('role') == $role->value ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }} {{-- Use ucfirst($role->name) for better display --}}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4"> {{-- Changed justify-between to justify-end and added gap --}}
                        <a href="{{ route('admin.users.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection