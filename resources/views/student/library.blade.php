<x-student-layout>

    {{-- This is how you pass content to the 'header' slot in the component --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Library Resources') }}
        </h2>
    </x-slot>

    {{-- All content that was previously inside @section('content') goes directly here --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-4">Your Borrowed Books</h3>
                    @if ($borrowedBooks->isEmpty())
                        <p class="text-gray-600">You currently have no books borrowed.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrowed On</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due On</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($borrowedBooks as $loan)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->book->title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->book->author }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $loan->borrowed_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap @if($loan->due_at && $loan->due_at->isPast()) text-red-600 font-semibold @endif">
                                                {{ $loan->due_at ? $loan->due_at->format('M d, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('student.library.return', $loan) }}" method="POST" onsubmit="return confirm('Are you sure you want to return this book?');">
                                                    @csrf
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900">Return</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-semibold mb-4">Available Books</h3>

                    <form action="{{ route('student.library.search') }}" method="GET" class="mb-4 flex items-center">
                        <input type="text" name="query" placeholder="Search by title, author, or ISBN"
                               value="{{ request('query') }}"
                               class="flex-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mr-2">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Search</button>
                    </form>

                    @if ($books->isEmpty())
                        <p class="text-gray-600">No books found matching your search or currently available.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($books as $book)
                                <div class="border rounded-lg p-4 flex flex-col items-center text-center shadow-sm">
                                    @if ($book->cover_image_url)
                                        <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }} cover" class="w-24 h-32 object-cover mb-4 rounded shadow">
                                    @else
                                        <div class="w-24 h-32 bg-gray-200 flex items-center justify-center text-gray-500 text-sm mb-4 rounded">No Cover</div>
                                    @endif
                                    <h4 class="font-semibold text-lg">{{ $book->title }}</h4>
                                    <p class="text-gray-600 text-sm">by {{ $book->author }}</p>
                                    <p class="text-gray-500 text-xs mt-1">ISBN: {{ $book->isbn ?? 'N/A' }}</p>
                                    <p class="text-gray-700 text-sm mt-2">Available: {{ $book->available_copies }}</p>

                                    {{-- Check if the book is available and the user hasn't already borrowed it --}}
                                    @if ($book->available_copies > 0 && !Auth::user()->loans->where('book_id', $book->id)->whereNull('returned_at')->count())
                                        <form action="{{ route('student.library.borrow', $book) }}" method="POST" class="mt-4">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">Borrow</button>
                                        </form>
                                    @else
                                        <button class="px-4 py-2 bg-gray-400 text-white rounded-md mt-4 cursor-not-allowed" disabled>Not Available</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $books->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-student-layout>