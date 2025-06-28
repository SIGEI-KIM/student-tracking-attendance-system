<x-student-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('IT Support') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> {{-- Reduced max-width for a more focused card --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"> {{-- Stronger shadow --}}
                <div class="p-8 md:p-10 text-gray-900"> {{-- Increased padding --}}
                    <h3 class="text-3xl font-extrabold text-indigo-700 mb-6 text-center"> {{-- Larger, bolder, centered heading, brand color --}}
                        <i class="fas fa-headset mr-3"></i> Contact IT Support
                    </h3>

                    <p class="mb-6 text-lg text-gray-700 text-center"> {{-- Larger text, centered --}}
                        If you are experiencing any technical issues with the system, please contact our IT Support team using the details below. We're here to help!
                    </p>

                    <div class="space-y-6 mb-8"> {{-- Added vertical spacing between contact methods --}}
                        <div class="flex items-center bg-gray-50 p-4 rounded-md shadow-sm"> {{-- Styled contact items --}}
                            <div class="flex-shrink-0 mr-4">
                                <i class="fas fa-envelope text-indigo-500 text-2xl"></i> {{-- Email icon --}}
                            </div>
                            <div>
                                <strong class="text-gray-800 text-lg">Email:</strong>
                                <a href="mailto:support@attendancesystem.com" class="text-blue-600 hover:text-blue-800 hover:underline text-lg ml-2">support@attendancesystem.com</a>
                            </div>
                        </div>

                        <div class="flex items-center bg-gray-50 p-4 rounded-md shadow-sm"> {{-- Styled contact items --}}
                            <div class="flex-shrink-0 mr-4">
                                <i class="fas fa-phone-alt text-indigo-500 text-2xl"></i> {{-- Phone icon --}}
                            </div>
                            <div>
                                <strong class="text-gray-800 text-lg">Phone:</strong>
                                <span class="text-gray-700 text-lg ml-2">+254 7XX XXX XXX</span>
                            </div>
                        </div>

                        <div class="flex items-center bg-gray-50 p-4 rounded-md shadow-sm"> {{-- Styled contact items --}}
                            <div class="flex-shrink-0 mr-4">
                                <i class="fas fa-clock text-indigo-500 text-2xl"></i> {{-- Clock icon --}}
                            </div>
                            <div>
                                <strong class="text-gray-800 text-lg">Office Hours:</strong>
                                <span class="text-gray-700 text-lg ml-2">Monday - Friday, 8:00 AM - 5:00 PM (EAT)</span>
                            </div>
                        </div>
                    </div>

                    <p class="mt-8 text-md text-gray-600 text-center italic"> {{-- Centered, italicized, slightly smaller text --}}
                        "Please provide a detailed description of your issue, including any error messages or steps to reproduce the problem. Our team will get back to you as soon as possible."
                    </p>

                    <div class="mt-8 text-center">
                        <a href="mailto:support@attendancesystem.com" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                            <i class="far fa-paper-plane mr-2"></i> Send an Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-student-layout>