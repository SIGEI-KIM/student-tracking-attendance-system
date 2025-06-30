<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Alpine.js for dynamic UI components like the toast --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- SweetAlert2 CSS (for other types of alerts if you use them) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body class="font-sans antialiased bg-gray-50"> 
    <div class="min-h-screen">
        {{-- TOP NAVIGATION BAR --}}
        <nav x-data="{ open: false }" class="bg-teal-900 border-b border-teal-800"> 
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route(auth()->user()->isAdmin() ? 'admin.dashboard' : (auth()->user()->isLecturer() ? 'lecturer.dashboard' : 'student.dashboard')) }}">
                                <!-- <x-application-logo class="block h-9 w-auto fill-current text-white" />  -->
                            </a>
                        </div>

                        <div class="flex items-center space-x-8 -my-px ms-10">
                            @if(auth()->user()->isAdmin())
                                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-gray-300 hover:text-white hover:border-gray-300">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                            @elseif(auth()->user()->isLecturer())
                                <x-nav-link :href="route('lecturer.dashboard')" :active="request()->routeIs('lecturer.dashboard')" class="text-gray-300 hover:text-white hover:border-gray-300">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                            @else {{-- Student User --}}
                                <x-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')" class="text-gray-300 hover:text-white hover:border-gray-300">
                                    {{ __('Dashboard') }}
                                </x-nav-link>
                            @endif

                            @if(!auth()->user()->isAdmin())
                                <div class="hidden space-x-8 sm:flex">
                                    @if(auth()->user()->isLecturer())
                                        <x-nav-link :href="route('lecturer.attendance.index')" :active="request()->routeIs('lecturer.attendance.*')" class="text-gray-300 hover:text-white hover:border-gray-300">
                                            {{ __('Attendance') }}
                                        </x-nav-link>
                                    @else {{-- Student User --}}
                                        <x-nav-link :href="route('student.select-course')" :active="request()->routeIs('student.select-course')" class="text-gray-300 hover:text-white hover:border-gray-300">
                                            {{ __('Select Course') }}
                                        </x-nav-link>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-transparent hover:text-white focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-teal-800 focus:outline-none focus:bg-teal-800 focus:text-white transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Responsive Navigation Menu --}}
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-teal-800"> 
                <div class="pt-2 pb-3 space-y-1">
                    @if(auth()->user()->isAdmin())
                        <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-gray-200 hover:text-white hover:bg-teal-700">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="text-gray-200 hover:text-white hover:bg-teal-700">
                            {{ __('Users') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')" class="text-gray-200 hover:text-white hover:bg-teal-700">
                            {{ __('Courses') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.lecturers.index')" :active="request()->routeIs('admin.lecturers.*')" class="text-gray-200 hover:text-white hover:bg-teal-700">
                            {{ __('Lecturers') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.levels.index')" :active="request()->routeIs('admin.levels.*')" class="text-gray-200 hover:text-white hover:bg-teal-700">
                            {{ __('Levels') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('admin.units.index')" :active="request()->routeIs('admin.units.*')" class="text-gray-200 hover:text-white hover:bg-teal-700">
                            {{ __('Units') }}
                        </x-responsive-nav-link>
                    @elseif(auth()->user()->isLecturer())
                        <x-responsive-nav-link :href="route('lecturer.dashboard')" :active="request()->routeIs('lecturer.dashboard')" class="text-gray-200 hover:text-white hover:bg-teal-700">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('lecturer.attendance.index')" :active="request()->routeIs('lecturer.attendance.*')" class="text-gray-200 hover:text-white hover:bg-teal-700">
                            {{ __('Attendance') }}
                        </x-responsive-nav-link>
                    @else {{-- Student User --}}
                        <x-responsive-nav-link :href="route('student.dashboard')" :active="request()->routeIs('student.dashboard')" class="text-gray-200 hover:text-white hover:bg-teal-700">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('student.select-course')" :active="request()->routeIs('student.select-course')" class="text-gray-200 hover:text-white hover:bg-teal-700">
                            {{ __('Select Course') }}
                        </x-responsive-nav-link>
                    @endif
                </div>

                <div class="pt-4 pb-1 border-t border-teal-700"> 
                    <div class="px-4">
                        <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div> 
                        <div class="font-medium text-sm text-gray-300">{{ Auth::user()->email }}</div> 
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')" class="text-gray-200 hover:text-white hover:bg-teal-700"> 
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-gray-200 hover:text-white hover:bg-teal-700"> 
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main content area (sidebar and content) --}}
        <div class="flex">
            {{-- Admin Sidebar --}}
            <div class="hidden md:flex md:w-64 md:flex-col">
                <div class="flex flex-col h-0 flex-1 border-r border-gray-200 bg-white"> 
                    <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                        @include('admin.layouts.sidebar') 
                    </div>
                </div>
            </div>

            {{-- Main content area --}}
            <main class="flex-1 p-8 bg-gray-50"> 
                @yield('content')
            </main>
        </div>
    </div>

    {{-- TOAST NOTIFICATION AREA - NOW AT THE TOP --}}
    <div x-data="{ show: false, message: '', type: '' }"
         x-init="
            @if(session()->has('success'))
                show = true;
                message = '{{ session('success') }}';
                type = 'success';
                setTimeout(() => show = false, 3000); // Hide after 3 seconds
            @elseif(session()->has('error'))
                show = true;
                message = '{{ session('error') }}';
                type = 'error';
                setTimeout(() => show = false, 3000);
            @endif
         "
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-full" {{-- Changed to -translate-y-full --}}
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-full" {{-- Changed to -translate-y-full --}}
         class="fixed top-0 right-0 m-4 p-4 rounded-lg shadow-lg text-white z-50 min-w-[250px]" {{-- Changed bottom-0 to top-0 --}}
         :class="{
            'bg-green-600': type === 'success',
            'bg-red-600': type === 'error',
            'bg-blue-600': type === 'info'
         }"
         style="display: none;">
        
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <template x-if="type === 'success'">
                    <svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </template>
                <template x-if="type === 'error'">
                    <svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2A9 9 0 111 10a9 9 0 0118 0z"></path></svg>
                </template>
                <span x-text="message" class="font-medium text-sm sm:text-base"></span>
            </div>
            <button @click="show = false" class="ml-4 text-white hover:text-gray-200 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
    {{-- END TOAST NOTIFICATION AREA --}}

    {{-- SweetAlert2 JS (for other types of alerts if you use them) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>