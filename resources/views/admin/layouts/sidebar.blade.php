<aside id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-teal-700 border-r border-teal-800 md:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-teal-700">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="
                       flex items-center p-2 text-base font-normal rounded-lg
                       @if(request()->routeIs('admin.dashboard'))
                           bg-teal-800 text-white
                       @else
                           text-white hover:bg-teal-600
                       @endif
                       group transition-all duration-200 ease-in-out hover:scale-[1.02] hover:shadow-lg
                   ">
                    <svg class="w-6 h-6 transition-colors duration-200 group-hover:text-white
                        @if(request()->routeIs('admin.dashboard'))
                            text-white
                        @else
                            text-teal-200
                        @endif"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.lecturers.create') }}"
                   class="
                       flex items-center p-2 text-base font-normal rounded-lg
                       @if(request()->routeIs('admin.lecturers.create') || request()->routeIs('admin.lecturers.edit') || request()->routeIs('admin.lecturers.index') ) {{-- Added index to lecturers group --}}
                           bg-teal-800 text-white
                       @else
                           text-white hover:bg-teal-600
                       @endif
                       group transition-all duration-200 ease-in-out hover:scale-[1.02] hover:shadow-lg
                   ">
                    <svg class="w-6 h-6 transition-colors duration-200 group-hover:text-white
                        @if(request()->routeIs('admin.lecturers.*')) {{-- Simpler wildcard for all lecturer routes --}}
                            text-white
                        @else
                            text-teal-200
                        @endif"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        <path d="M14 9a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V9z"></path>
                    </svg>
                    <span class="ml-3">Register Lecturer</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.users.index') }}"
                   class="
                       flex items-center p-2 text-base font-normal rounded-lg
                       @if(request()->routeIs('admin.users.*'))
                           bg-teal-800 text-white
                       @else
                           text-white hover:bg-teal-600
                       @endif
                       group transition-all duration-200 ease-in-out hover:scale-[1.02] hover:shadow-lg
                   ">
                    <svg class="w-6 h-6 transition-colors duration-200 group-hover:text-white
                        @if(request()->routeIs('admin.users.*'))
                            text-white
                        @else
                            text-teal-200
                        @endif"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                    <span class="ml-3">Users</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.courses.index') }}"
                   class="
                       flex items-center p-2 text-base font-normal rounded-lg
                       @if(request()->routeIs('admin.courses.*'))
                           bg-teal-800 text-white
                       @else
                           text-white hover:bg-teal-600
                       @endif
                       group transition-all duration-200 ease-in-out hover:scale-[1.02] hover:shadow-lg
                   ">
                    <svg class="w-6 h-6 transition-colors duration-200 group-hover:text-white
                        @if(request()->routeIs('admin.courses.*'))
                            text-white
                        @else
                            text-teal-200
                        @endif"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2H4a1 1 0 010-2V4zm3 1h2v2H7V5zm0 4h2v2H7V9zm0 4h2v2H7v-2z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Courses</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.levels.index') }}"
                   class="
                       flex items-center p-2 text-base font-normal rounded-lg
                       @if(request()->routeIs('admin.levels.*'))
                           bg-teal-800 text-white
                       @else
                           text-white hover:bg-teal-600
                       @endif
                       group transition-all duration-200 ease-in-out hover:scale-[1.02] hover:shadow-lg
                   ">
                    <svg class="w-6 h-6 transition-colors duration-200 group-hover:text-white
                        @if(request()->routeIs('admin.levels.*'))
                            text-white
                        @else
                            text-teal-200
                        @endif"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Levels</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.units.index') }}"
                   class="
                       flex items-center p-2 text-base font-normal rounded-lg
                       @if(request()->routeIs('admin.units.*'))
                           bg-teal-800 text-white
                       @else
                           text-white hover:bg-teal-600
                       @endif
                       group transition-all duration-200 ease-in-out hover:scale-[1.02] hover:shadow-lg
                   ">
                    <svg class="w-6 h-6 transition-colors duration-200 group-hover:text-white
                        @if(request()->routeIs('admin.units.*'))
                            text-white
                        @else
                            text-teal-200
                        @endif"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm2-1a1 1 0 00-1 1v10a1 1 0 001 1h10a1 1 0 001-1V5a1 1 0 00-1-1H5z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Units</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.course_report_submissions.index') }}"
                   class="
                       flex items-center p-2 text-base font-normal rounded-lg
                       {{-- Updated route check to match the new route name --}}
                       @if(request()->routeIs('admin.course_report_submissions.*'))
                           bg-teal-800 text-white
                       @else
                           text-white hover:bg-teal-600
                       @endif
                       group transition-all duration-200 ease-in-out hover:scale-[1.02] hover:shadow-lg
                   ">
                    <svg class="w-6 h-6 text-teal-200 transition-colors duration-200 group-hover:text-white
                        {{-- Updated icon color change to match the new route name --}}
                        @if(request()->routeIs('admin.course_report_submissions.*'))
                            text-white
                        @else
                            text-teal-200
                        @endif"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        {{-- Keeping the document/report icon from your previous suggestion or a similar one --}}
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 3.414L16.586 7A2 2 0 0117 8.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm6 6a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Lecturer Reports</span> {{-- Changed text to be more specific --}}
                </a>
            </li>
        </ul>
        <div class="h-24"></div>
        <ul class="pt-4 mt-4 space-y-2 border-t border-teal-800">
            <li>
                <form method="POST" action="{{ route('logout') }}"
                      class="flex items-center p-2 text-base font-normal rounded-lg w-full cursor-pointer
                           text-white hover:bg-teal-600 group transition-all duration-200 ease-in-out hover:scale-[1.02] hover:shadow-lg">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="flex items-center w-full">
                        <svg class="w-6 h-6 transition-colors duration-200 group-hover:text-white text-teal-200" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Log Out</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</aside>