@extends('layouts.admin') {{-- Make sure this extends your main admin layout --}}

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-3xl font-extrabold mb-8 text-gray-800 border-b-2 border-indigo-500 pb-2">Edit User: {{ $user->name }}</h2>
                
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                    @csrf
                    @method('PUT') {{-- Required for update method in controller --}}

                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="name">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="email">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                      @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="role">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role" required
                                class="block w-full px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm 
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                       @error('role') border-red-500 @enderror">
                            <option value="">Select Role</option>
                            @foreach($roles as $role) {{-- Loop through the roles passed from controller --}}
                                <option value="{{ $role->value }}" {{ old('role', $user->role->value) == $role->value ? 'selected' : '' }}>
                                    {{ ucfirst($role->value) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" 
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md 
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
                                       transition ease-in-out duration-150 shadow-md">
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection