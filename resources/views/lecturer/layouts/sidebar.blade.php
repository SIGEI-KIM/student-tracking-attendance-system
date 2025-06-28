<aside id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-gray-800 border-r border-gray-700 md:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-800 flex flex-col justify-between">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('lecturer.dashboard') }}"
                   class="flex items-center p-2 text-base font-normal rounded-lg group
                          {{ Request::routeIs('lecturer.dashboard') ? 'bg-gray-700 text-white' : 'text-gray-200 hover:bg-gray-700' }}
                          transition-colors duration-300 ease-in-out">
                    <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lecturer.attendance.index') }}"
                   class="flex items-center p-2 text-base font-normal rounded-lg group
                          {{ Request::routeIs('lecturer.attendance.index') || Request::routeIs('lecturer.attendance.unit.view') ? 'bg-gray-700 text-white' : 'text-gray-200 hover:bg-gray-700' }}
                          transition-colors duration-300 ease-in-out">
                    <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Attendance Records</span>
                </a>
            </li>

            <li>
                <a href="{{ route('lecturer.attendance.create') }}"
                   class="flex items-center p-2 text-base font-normal rounded-lg group
                          {{ Request::routeIs('lecturer.attendance.create') ? 'bg-gray-700 text-white' : 'text-gray-200 hover:bg-gray-700' }}
                          transition-colors duration-300 ease-in-out">
                    <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM5.3 8.3c-.4-.4-1-.4-1.4 0s-.4 1 0 1.4L8.5 12l-4.6 4.6c-.4.4-.4 1 0 1.4s1 .4 1.4 0L10 13.4l4.6 4.6c.4.4 1 .4 1.4 0s.4-1 0-1.4L11.5 12l4.6-4.6c.4-.4.4-1 0-1.4s-1-.4-1.4 0L10 10.6l-4.7-4.7z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v7a1 1 0 11-2 0V3a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-3">Generate Attendance Code</span>
                </a>
            </li>

            <li>
                <a href="{{ route('lecturer.reports.index') }}"
                   class="flex items-center p-2 text-base font-normal rounded-lg group
                          {{ Request::routeIs('lecturer.reports.index') || Request::routeIs('lecturer.reports.*') ? 'bg-gray-700 text-white' : 'text-gray-200 hover:bg-gray-700' }}
                          transition-colors duration-300 ease-in-out">
                    <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Reports</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lecturer.report_submission.create') }}"
                   class="flex items-center p-2 text-base font-normal rounded-lg group
                          {{ Request::routeIs('lecturer.report_submission.*') ? 'bg-gray-700 text-white' : 'text-gray-200 hover:bg-gray-700' }}
                          transition-colors duration-300 ease-in-out">
                    <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0h.01M12 3v2.25A2.25 2.25 0 0014.25 7h2.25M21 12v7.5A2.25 2.25 0 0118.75 22H5.25A2.25 2.25 0 013 19.5V4.5A2.25 2.25 0 015.25 2.25H9M13.5 12a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"></path>
                    </svg>
                    <span class="ml-3">Submit Course Report</span>
                </a>
            </li>
            <li>
                <a href="{{ route('lecturer.announcements.index') }}"
                   class="flex items-center p-2 text-base font-normal rounded-lg group
                          {{ Request::routeIs('lecturer.announcements.*') ? 'bg-gray-700 text-white' : 'text-gray-200 hover:bg-gray-700' }}
                          transition-colors duration-300 ease-in-out">
                    <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.76-1.39A6.46 6.46 0 005.166 13H4a2 2 0 00-2 2v2a2 2 0 002 2h.138A9.774 9.774 0 0010 20a9.998 9.998 0 009-10h-1zm-6.9-4a1 1 0 00-2 0v2a1 1 0 002 0V6z" clip-rule="evenodd"></path>
                        <path d="M16.53 9.776a.999.999 0 00-.7-.417H15V6a3 3 0 00-3-3V2a1 1 0 00-2 0v1a3 3 0 00-3 3v3.359h-.832a.999.999 0 00-.702.417 1 1 0 00.146 1.487l.088.088A5.474 5.474 0 0010 13a5.474 5.474 0 003.882-1.55l.088-.088a1 1 0 00.146-1.487z"></path>
                    </svg>
                    <span class="ml-3">Announcements</span>
                </a>
            </li>
        </ul>

        <ul class="space-y-2 mt-auto">
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       class="flex items-center p-2 text-base font-normal text-red-300 rounded-lg hover:bg-gray-700 hover:text-red-100 group
                              transition-colors duration-300 ease-in-out"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <svg class="w-6 h-6 text-red-400 transition duration-75 group-hover:text-red-200" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Log Out</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</aside>