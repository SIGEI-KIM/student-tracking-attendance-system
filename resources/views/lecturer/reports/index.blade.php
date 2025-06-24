@extends('layouts.lecturer')

@section('content')
<div class="py-6 sm:py-8 md:py-10 lg:py-12">
    <div class="max-w-xl sm:max-w-3xl md:max-w-5xl lg:max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-4 sm:p-6 lg:p-8 bg-white border-b border-gray-200">
                <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 sm:mb-6 flex items-center">
                    <svg class="w-7 h-7 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-4m3 4v-4m3 4v-4m-9 8h.01M12 17a3 3 0 100-6 3 3 0 000 6zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Reports
                </h2>

                {{-- Report Summaries Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8 lg:mb-10">

                    {{-- Total Units Card --}}
                    <div class="border border-blue-300 rounded-lg p-5 sm:p-6 bg-blue-50 text-blue-800 shadow-md transform transition duration-300 hover:scale-105 hover:shadow-lg">
                        {{-- Make sure no 'a' tag here wrapping the entire card --}}
                        <h3 class="font-semibold text-lg sm:text-xl mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            Total Units
                        </h3>
                        <p class="text-3xl sm:text-4xl font-extrabold">{{ $units->count() }}</p>
                        <div class="text-right mt-3">
                            {{-- This 'a' tag should be the ONLY clickable element for this card --}}
                            <a href="{{ route('lecturer.units.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium transition duration-200">
                                View All <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>

                    {{-- Total Attendance Records Card --}}
                    <div class="border border-green-300 rounded-lg p-5 sm:p-6 bg-green-50 text-green-800 shadow-md transform transition duration-300 hover:scale-105 hover:shadow-lg">
                        {{-- Make sure no 'a' tag here wrapping the entire card --}}
                        <h3 class="font-semibold text-lg sm:text-xl mb-2 flex items-center">
                             <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Total Attendance Records
                        </h3>
                        <p class="text-3xl sm:text-4xl font-extrabold">{{ $totalAttendanceRecords }}</p>
                        <div class="text-right mt-3">
                            {{-- This 'a' tag should be the ONLY clickable element for this card --}}
                            <a href="{{ route('lecturer.attendance_records.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium transition duration-200">
                                View All <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                    {{-- Add more report summaries here with similar styling --}}
                </div>

                <h3 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-5 border-b-2 pb-3 border-indigo-300 flex items-center">
                    <svg class="w-6 h-6 text-indigo-600 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10m-9 4h4m-3 2h6m-7-2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2h14a2 2 0 012 2v5a2 2 0 01-2 2h-3"></path></svg>
                    Report Options
                </h3>
                <div class="space-y-4 sm:space-y-6">
                    <div class="bg-gray-50 p-5 sm:p-6 rounded-lg shadow-inner border border-gray-100">
                        <h4 class="font-bold text-lg sm:text-xl mb-2 sm:mb-3 text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Attendance Summary by Unit
                        </h4>
                        <p class="text-gray-700 mb-3 text-sm sm:text-base">View a summary of attendance for each of your assigned units.</p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-700">
                            @forelse($units as $unit)
                                <li class="text-sm sm:text-base">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-800 hover:underline font-medium transition duration-200">
                                        {{ $unit->name }} ({{ $unit->code }})
                                    </a>
                                </li>
                            @empty
                                <p class="text-gray-500 italic text-sm sm:text-base">No units assigned to generate reports for.</p>
                            @endforelse
                        </ul>
                    </div>

                    {{-- Custom Report Generation Form --}}
                    <div class="bg-gray-50 p-5 sm:p-6 rounded-lg shadow-inner border border-gray-100">
                        <h4 class="font-bold text-lg sm:text-xl mb-2 sm:mb-3 text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Generate Custom Report
                        </h4>
                        <p class="text-gray-700 mb-4 text-sm sm:text-base">Select criteria to generate a detailed attendance report.</p>
                        <form action="{{ route('lecturer.reports.generateUnitReportPdf') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="report_type" class="block text-sm font-medium text-gray-700 mb-1">Report Type:</label>
                                <select id="report_type" name="report_type" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                                    <option value="">Select a report type</option>
                                    <option value="daily">Daily Attendance</option>
                                    <option value="weekly">Weekly Attendance</option>
                                    <option value="student_summary">Student Summary</option>
                                </select>
                            </div>
                            <div class="mb-5">
                                <label for="unit_select" class="block text-sm font-medium text-gray-700 mb-1">Select Unit (Optional):</label>
                                <select id="unit_select" name="unit_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md shadow-sm">
                                    <option value="">All Units</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="inline-flex items-center justify-center py-2 px-5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:scale-105">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Generate Report
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection