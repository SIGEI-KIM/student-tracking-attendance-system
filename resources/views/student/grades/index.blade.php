<x-student-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Grades') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-xl rounded-lg p-6 mb-8 text-center transform hover:scale-105 transition-transform duration-300 ease-in-out">
            <h3 class="text-3xl font-extrabold mb-2">Overall Academic Performance</h3>
            <p class="text-lg opacity-90">View your detailed grades for all units you are currently enrolled in or have completed.</p>
        </div>

        @forelse($enrolledUnits as $unit)
            <div class="bg-white shadow-xl rounded-lg p-6 mb-6 border-t-4 border-indigo-500">
                <h3 class="text-2xl font-bold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-book mr-3 text-indigo-600"></i>
                    {{ $unit->name }} <span class="text-indigo-600 ml-2">({{ $unit->code }})</span>
                </h3>
                <p class="text-gray-600 text-md mb-4 border-b pb-4 border-gray-200">
                    <span class="font-semibold">Course:</span> {{ $unit->course->name ?? 'N/A' }}
                    <span class="mx-3 text-gray-400">|</span>
                    <span class="font-semibold">Level:</span> {{ $unit->level->name ?? 'N/A' }}
                    <span class="mx-3 text-gray-400">|</span>
                    <span class="font-semibold">Semester:</span> {{ $unit->semester->name ?? 'N/A' }}
                </p>

                @if($unit->grades->isNotEmpty())
                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full bg-white border-collapse"> {{-- Removed outer border, using cell borders --}}
                            <thead>
                                <tr class="bg-gray-100 text-left text-gray-700 uppercase text-sm leading-normal border-b border-gray-300">
                                    <th class="py-3 px-6 font-bold text-gray-700 rounded-tl-lg">Grade Type</th>
                                    <th class="py-3 px-6 font-bold text-gray-700">Score</th>
                                    <th class="py-3 px-6 font-bold text-gray-700">Max Score</th>
                                    <th class="py-3 px-6 font-bold text-gray-700 rounded-tr-lg">Percentage</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-800 text-base">
                                @foreach($unit->grades as $grade)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                        <td class="py-4 px-6">{{ $grade->grade_type ?? 'Overall Grade' }}</td>
                                        <td class="py-4 px-6">{{ number_format($grade->score, 2) }}</td>
                                        <td class="py-4 px-6">{{ number_format($grade->max_score, 2) ?? 'N/A' }}</td>
                                        <td class="py-4 px-6">
                                            @if($grade->max_score > 0)
                                                @php
                                                    $percentage = ($grade->score / $grade->max_score) * 100;
                                                @endphp
                                                <span class="font-extrabold text-lg {{ $percentage >= 50 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($percentage, 2) }}%
                                                </span>
                                            @else
                                                <span class="text-gray-500 italic">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 rounded-md" role="alert">
                        <div class="flex items-center">
                            <div class="py-1">
                                <i class="fas fa-info-circle text-xl mr-3"></i>
                            </div>
                            <div>
                                <p class="font-bold">No Grades Yet!</p>
                                <p class="text-sm">No grades have been recorded for this unit at this time. Please check back later.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-6 rounded-lg shadow-md" role="alert">
                <div class="flex items-center justify-center">
                    <div class="py-1">
                        <i class="fas fa-exclamation-triangle text-2xl mr-4"></i>
                    </div>
                    <div>
                        <p class="font-bold text-lg mb-1">No Units Found</p>
                        <p class="text-base">You are not currently enrolled in any units, or no units with grades could be found.</p>
                        <p class="text-sm mt-2">If you believe this is an error, please contact your academic advisor.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</x-student-layout>