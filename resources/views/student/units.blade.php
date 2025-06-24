
@extends('layouts.student')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">Units for {{ $units->first()->course->name ?? '' }} - {{ $units->first()->level->name ?? '' }}</h2>
                
                @if($units->isEmpty())
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">No units found for this course and level.</span>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($units as $unit)
                            <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
                                <h4 class="text-lg font-bold mb-2">{{ $unit->name }}</h4>
                                <p class="text-gray-600 mb-4">{{ $unit->code }}</p>
                                
                                <div class="mb-4">
                                    <h5 class="font-semibold">Lecturers:</h5>
                                    <ul class="list-disc list-inside text-sm">
                                        @foreach($unit->lecturers as $lecturer)
                                            <li>{{ $lecturer->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                
                                <a href="{{ route('student.mark-attendance', $unit) }}" 
                                   class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Mark Attendance
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection