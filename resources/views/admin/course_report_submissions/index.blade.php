@extends('layouts.admin')

@section('content')
<div class="py-6 sm:py-8 md:py-10 lg:py-12 bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
            <div class="p-4 sm:p-6 lg:p-8 bg-white">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-gray-800 mb-6 flex items-center">
                    <svg class="w-8 h-8 text-indigo-700 mr-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 3.414L16.586 7A2 2 0 0117 8.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm6 6a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Lecturer Course Reports
                </h2>

                {{-- Session Messages --}}
                <div class="mb-6 space-y-4">
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative shadow-sm" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                </div>

                @if ($submissions->isEmpty())
                    <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 rounded-md shadow-sm" role="alert">
                        <p class="font-bold text-lg mb-1">No Reports Yet</p>
                        <p class="text-base">There are no course reports submitted by lecturers at this time.</p>
                    </div>
                @else
                    <div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lecturer</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted On</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reviewed</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($submissions as $submission)
                                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $submission->lecturer->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $submission->course->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $submission->unit->name ?? 'N/A' }} ({{ $submission->unit->code ?? 'N/A' }})
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('admin.course_report_submissions.download', $submission->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline font-semibold">
                                                {{ $submission->file_name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $submission->submitted_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $submission->remarks }}">
                                            {{ $submission->remarks ?? 'No remarks' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $submission->is_reviewed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $submission->is_reviewed ? 'Yes' : 'No' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex flex-col space-y-2">
                                                {{-- Mark as Reviewed/Unreviewed Form --}}
                                                <form action="{{ route('admin.course_report_submissions.update_status', $submission->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="is_reviewed" value="{{ $submission->is_reviewed ? '0' : '1' }}">
                                                    <button type="submit" class="w-full text-center py-1.5 px-3 rounded-md text-sm font-semibold {{ $submission->is_reviewed ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }} transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-opacity-75 {{ $submission->is_reviewed ? 'focus:ring-yellow-500' : 'focus:ring-green-500' }}">
                                                        {{ $submission->is_reviewed ? 'Mark Unreviewed' : 'Mark Reviewed' }}
                                                    </button>
                                                </form>

                                                {{-- Delete Form (MODIFIED FOR SWEETALERT) --}}
                                                <form id="delete-form-{{ $submission->id }}" action="{{ route('admin.course_report_submissions.destroy', $submission->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete({{ $submission->id }})" class="w-full text-center py-1.5 px-3 rounded-md text-sm font-semibold bg-red-500 hover:bg-red-600 text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 focus:ring-opacity-75">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination links --}}
                    <div class="mt-6">
                        {{ $submissions->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts') {{-- Or @section('scripts') if your layout uses that --}}
<script>
    function confirmDelete(submissionId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! The report file will also be deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d', 
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + submissionId).submit();
            }
        });
    }
</script>
@endpush
@endsection