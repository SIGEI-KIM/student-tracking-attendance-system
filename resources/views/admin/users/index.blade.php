@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">User Management</h1>

        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('admin.users.create') }}" class="bg-teal-700 hover:bg-teal-800 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                Add New User
            </a>
        </div>

        <div x-data="{ activeTab: 'students' }" class="bg-white p-6 rounded-lg shadow-md">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="activeTab = 'students'"
                            :class="{ 'border-teal-500 text-teal-600': activeTab === 'students', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'students' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                        Students
                    </button>
                    <button @click="activeTab = 'lecturers'"
                            :class="{ 'border-teal-500 text-teal-600': activeTab === 'lecturers', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'lecturers' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                        Lecturers
                    </button>
                    <button @click="activeTab = 'admins'"
                            :class="{ 'border-teal-500 text-teal-600': activeTab === 'admins', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'admins' }"
                            class="whitespace-now-ap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                        Admins
                    </button>
                </nav>
            </div>

            <div x-show="activeTab === 'students'" class="py-6">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">All Students</h2>
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">Name</th>
                                <th scope="col" class="py-3 px-6">Email</th>
                                <th scope="col" class="py-3 px-6">Reg. No.</th>
                                <th scope="col" class="py-3 px-6">ID Number</th>
                                <th scope="col" class="py-3 px-6">Gender</th>
                                <th scope="col" class="py-3 px-6">Course(s)</th> {{-- Changed to Course(s) --}}
                                <th scope="col" class="py-3 px-6">Profile Status</th>
                                <th scope="col" class="py-3 px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $user)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="py-4 px-6">{{ $user->email }}</td>
                                    {{-- ACCESSING STUDENT PROFILE DETAILS VIA THE 'student' RELATIONSHIP --}}
                                    <td class="py-4 px-6">{{ $user->student->registration_number ?? 'N/A' }}</td>
                                    <td class="py-4 px-6">{{ $user->student->id_number ?? 'N/A' }}</td>
                                    <td class="py-4 px-6">{{ $user->student->gender ?? 'N/A' }}</td>
                                    {{-- CORRECTED: DISPLAY ENROLLED COURSE(S) VIA $user->student->courses --}}
                                    <td class="py-4 px-6">
                                        @if($user->student && $user->student->courses->isNotEmpty()) {{-- --}}
                                            @foreach($user->student->courses as $course) {{-- --}}
                                                {{ $course->name }} ({{ $course->abbreviation ?? $course->code }})@unless($loop->last), @endunless<br>
                                            @endforeach
                                        @else
                                            Not Enrolled
                                        @endif
                                    </td>
                                    {{-- Profile Status (still on User model) --}}
                                    <td class="py-4 px-6">
                                        @if ($user->profile_completed)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Complete
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Incomplete
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="font-medium text-blue-600 hover:underline mr-3">Edit</a>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-form-{{ $user->id }}')" class="font-medium text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-4 px-6 text-center text-gray-500">No students found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Content for Lecturers Tab --}}
            <div x-show="activeTab === 'lecturers'" class="py-6" style="display: none;">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">All Lecturers</h2>
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">Name</th>
                                <th scope="col" class="py-3 px-6">Email</th>
                                <th scope="col" class="py-3 px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lecturers as $user)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="py-4 px-6">{{ $user->email }}</td>
                                    <td class="py-4 px-6">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="font-medium text-blue-600 hover:underline mr-3">Edit</a>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-form-{{ $user->id }}')" class="font-medium text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 px-6 text-center text-gray-500">No lecturers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Content for Admins Tab (Optional, if you want to differentiate) --}}
            <div x-show="activeTab === 'admins'" class="py-6" style="display: none;">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">All Administrators</h2>
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">Name</th>
                                <th scope="col" class="py-3 px-6">Email</th>
                                <th scope="col" class="py-3 px-6">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $user)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">{{ $user->name }}</td>
                                    <td class="py-4 px-6">{{ $user->email }}</td>
                                    <td class="py-4 px-6">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="font-medium text-blue-600 hover:underline mr-3">Edit</a>
                                        <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-form-{{ $user->id }}')" class="font-medium text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 px-6 text-center text-gray-500">No administrators found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- SweetAlert Script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', 
                cancelButtonColor: '#6c757d', 
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endsection