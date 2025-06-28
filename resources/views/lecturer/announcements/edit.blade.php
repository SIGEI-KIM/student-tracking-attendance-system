@extends('layouts.lecturer')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Edit Announcement') }}
    </h2>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 lg:p-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-6 text-center">
                Edit Announcement
            </h1>

            <form action="{{ route('lecturer.announcements.update', $announcement) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT') {{-- Use PUT method for update --}}

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Title:
                    </label>
                    <input type="text" name="title" id="title" required autofocus
                           class="mt-1 block w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base text-gray-900 dark:text-gray-100"
                           value="{{ old('title', $announcement->title) }}">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                        Content:
                    </label>
                    <textarea name="content" id="content" rows="8" required
                              class="mt-1 block w-full px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-base text-gray-900 dark:text-gray-100">{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    {{-- HIDDEN INPUT ADDED HERE --}}
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-200">
                        Make this announcement active (visible to students)
                    </label>
                </div>
                @error('is_active')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror

                <div class="flex justify-end mt-6">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150 transform hover:scale-105">
                        <svg class="-ml-1 mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Update Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection