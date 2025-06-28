<x-student-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Announcements') }}
        </h2>
    </x-slot>

    {{-- Main page background changed to a very dark, soft gray/blue-gray --}}
    <div class="py-8 sm:py-12 bg-gray-100 dark:bg-[#1E293B] min-h-screen"> {{-- Custom very dark blue-gray --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Main content container background changed to a lighter shade of the dark theme --}}
            <div class="bg-white dark:bg-[#2D3748] overflow-hidden shadow-2xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-3xl font-extrabold text-indigo-700 dark:text-indigo-400 mb-8 text-center pb-4 border-b-2 border-indigo-200 dark:border-indigo-700">
                        Latest Announcements
                    </h3>

                    @forelse($announcements as $announcement)
                        {{-- Individual announcement card background slightly lighter than main container --}}
                        <div class="bg-white dark:bg-[#374151] p-6 rounded-xl shadow-lg mb-6 last:mb-0 transform transition-all duration-300 hover:shadow-xl hover:translate-y-[-2px] border border-gray-200 dark:border-gray-600">
                            <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">
                                {{ $announcement->title }}
                            </h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 flex items-center flex-wrap">
                                <i class="far fa-calendar-alt mr-2 text-base text-blue-400 dark:text-blue-300"></i>
                                <span class="text-gray-700 dark:text-gray-300">{{ $announcement->created_at->format('M d, Y') }}</span>
                                @if($announcement->lecturer && ($announcement->lecturer->user->name ?? false))
                                    <span class="mx-3 text-gray-400 dark:text-gray-500">|</span>
                                    <i class="fas fa-user-tie mr-2 text-base text-emerald-400 dark:text-emerald-300"></i> Posted by:
                                    <span class="font-semibold text-gray-800 dark:text-gray-200 ml-1">{{ $announcement->lecturer->user->name }}</span>
                                @else
                                    <span class="mx-3 text-gray-400 dark:text-gray-500">|</span>
                                    <i class="fas fa-user-tie mr-2 text-base text-emerald-400 dark:text-emerald-300"></i> Posted by:
                                    <span class="font-semibold text-gray-800 dark:text-gray-200 ml-1">Lecturer</span>
                                @endif
                            </p>
                            <div class="text-gray-800 dark:text-gray-200 leading-relaxed text-base border-t border-gray-200 dark:border-gray-600 pt-4 mt-4">
                                {!! nl2br(e($announcement->content)) !!}
                            </div>
                        </div>
                    @empty
                        {{-- Empty state card background similar to main container --}}
                        <div class="text-gray-600 dark:text-gray-400 text-center py-12 px-4 bg-gray-50 dark:bg-[#2D3748] rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xl font-semibold mb-3 text-gray-700 dark:text-gray-300">No new announcements at this time.</p>
                            <p class="text-md text-gray-600 dark:text-gray-400">Please check back later for important updates.</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-student-layout>