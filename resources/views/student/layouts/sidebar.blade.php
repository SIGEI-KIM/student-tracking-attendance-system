<aside id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-gray-800 border-r border-gray-200 md:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-900 flex flex-col justify-between">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('student.dashboard') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-gray-700 group">
                    <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>
            {{-- Dynamically adjust link based on profile completion for 'Enroll in Course' --}}
            <li>
                <a href="{{ Auth::user()->profile_completed ? route('student.enroll.index') : route('student.profile.complete') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-gray-700 group">
                    <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Enroll in Course</span>
                </a>
            </li>
            {{-- NEW: My Registered Units link --}}
            <li>
                <a href="{{ route('student.units.index') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-gray-700 group">
                    <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 3.414L16.586 7A2 2 0 0118 8.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm4 6a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1zm1 3a1 1 0 100 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">My Units</span>
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-gray-700 group">
                    <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Attendance Records</span>
                </a>
            </li>
        </ul>

        {{-- Logout link moved to the bottom using flex utilities --}}
        <ul class="pt-4 mt-auto border-t border-gray-700">
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-gray-700 group w-full text-left">
                        <svg class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-2 0V4H4v12h12v-2a1 1 0 112 0v3a1 1 0 01-1 1H3a1 1 0 01-1-1V3zm9.354 5.354a.5.5 0 00-.708-.708L8.5 10.293 6.854 8.646a.5.5 0 10-.708.708l2 2a.5.5 0 00.708 0l3-3z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Log Out</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>