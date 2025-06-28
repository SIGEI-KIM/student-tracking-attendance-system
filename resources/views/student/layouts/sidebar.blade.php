<aside id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen pt-16 transition-transform -translate-x-full bg-[#202B3B] border-r border-[#304050] md:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-[#202B3B] flex flex-col justify-between">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('student.dashboard') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-[#304050] group">
                    <i class="fas fa-fw fa-tachometer-alt text-gray-400 group-hover:text-white"></i> {{-- Dashboard Icon --}}
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            {{-- EXISTING: Mark Attendance --}}
            <li>
                <a href="{{ route('student.attendance.index') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-[#304050] group">
                    <i class="fas fa-fw fa-calendar-check text-gray-400 group-hover:text-white"></i> {{-- Attendance Icon --}}
                    <span class="ml-3">Mark Attendance</span>
                </a>
            </li>

            {{-- EXISTING: Unit Catalog --}}
            <li>
                <a href="{{ route('student.units.catalog.index') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-[#304050] group">
                    <i class="fas fa-fw fa-book-open text-gray-400 group-hover:text-white"></i> {{-- Unit Catalog Icon --}}
                    <span class="ml-3">Unit Catalog</span>
                </a>
            </li>

            {{-- NEW LINK: View Grades/Results --}}
            <li>
                <a href="{{ route('student.grades.index') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-[#304050] group">
                    <i class="fas fa-fw fa-medal text-gray-400 group-hover:text-white"></i> {{-- Grades/Medal Icon --}}
                    <span class="ml-3">My Grades</span>
                </a>
            </li>

            {{-- NEW LINK: Course Registration/Enrollment --}}
            <li>
                <a href="{{ route('student.registration.index') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-[#304050] group">
                    <i class="fas fa-fw fa-file-signature text-gray-400 group-hover:text-white"></i> {{-- Registration Icon --}}
                    <span class="ml-3">Course Registration</span>
                </a>
            </li>

            {{-- NEW LINK: Timetable/Schedule --}}
            <li>
                <a href="{{ route('student.timetable.index') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-[#304050] group">
                    <i class="fas fa-fw fa-clock text-gray-400 group-hover:text-white"></i> {{-- Timetable Icon --}}
                    <span class="ml-3">My Timetable</span>
                </a>
            </li>

            {{-- NEW LINK: Fee Statement/Billing --}}
            <li>
                <a href="{{ route('student.fees.index') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-[#304050] group">
                    <i class="fas fa-fw fa-money-bill-alt text-gray-400 group-hover:text-white"></i> {{-- Fees Icon --}}
                    <span class="ml-3">Fee Statement</span>
                </a>
            </li>

            {{-- NEW LINK: Announcements/Notifications --}}
            <li>
                <a href="{{ route('student.announcements.index') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-[#304050] group">
                    <i class="fas fa-fw fa-bullhorn text-gray-400 group-hover:text-white"></i> {{-- Announcements Icon --}}
                    <span class="ml-3">Announcements</span>
                </a>
            </li>

            {{-- NEW LINK: Library Resources --}}
            <li>
                <a href="{{ route('student.library.index') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-[#304050] group">
                    <i class="fas fa-fw fa-book text-gray-400 group-hover:text-white"></i> {{-- Library Icon --}}
                    <span class="ml-3">Library Resources</span>
                </a>
            </li>

            {{-- NEW LINK: IT Support --}}
            <li>
                <a href="{{ route('student.it-support.index') }}" class="flex items-center p-2 text-base font-normal text-white rounded-lg hover:bg-[#304050] group">
                    <i class="fas fa-fw fa-headset text-gray-400 group-hover:text-white"></i> {{-- IT Support Icon --}}
                    <span class="ml-3">IT Support</span>
                </a>
            </li>

        </ul>

        {{-- Logout link moved to the bottom using flex utilities --}}
        <ul class="pt-4 mt-auto border-t border-[#304050]">
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center p-2 text-base font-normal text-red-400 rounded-lg hover:bg-[#304050] group w-full text-left">
                        <i class="fas fa-fw fa-sign-out-alt text-red-500 group-hover:text-red-300"></i> {{-- Logout Icon --}}
                        <span class="ml-3">Log Out</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>