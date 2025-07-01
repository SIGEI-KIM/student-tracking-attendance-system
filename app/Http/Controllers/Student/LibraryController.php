<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Book; 
use App\Models\Loan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryController extends Controller
{
    public function index()
    {
        // Fetch all available books (or paginate them for a large library)
        $books = Book::where('available_copies', '>', 0)->paginate(10);

        // Fetch books currently borrowed by the logged-in student
        $borrowedBooks = Auth::user()->loans()
                            ->whereNull('returned_at')
                            ->with('book') // Eager load book details
                            ->get();

        return view('student.library', compact('books', 'borrowedBooks'));
    }

    // Method to handle searching for books
    public function search(Request $request)
    {
        $query = $request->input('query');

        $books = Book::where('title', 'like', '%' . $query . '%')
                    ->orWhere('author', 'like', '%' . $query . '%')
                    ->orWhere('isbn', 'like', '%' . $query . '%')
                    ->where('available_copies', '>', 0)
                    ->paginate(10);

        $borrowedBooks = Auth::user()->loans()
                            ->whereNull('returned_at')
                            ->with('book')
                            ->get();

        return view('student.library', compact('books', 'borrowedBooks', 'query'));
    }

    // Method to handle borrowing a book
    public function borrow(Book $book)
    {
        // Basic validation: Is the book available and not already borrowed by this student?
        if ($book->available_copies > 0 && !Auth::user()->loans()->where('book_id', $book->id)->whereNull('returned_at')->exists()) {
            \DB::transaction(function () use ($book) {
                $book->decrement('available_copies');

                Loan::create([
                    'user_id' => Auth::id(),
                    'book_id' => $book->id,
                    'due_at' => now()->addWeeks(2), // Example: Due in 2 weeks
                ]);
            });

            return back()->with('success', 'Book "' . $book->title . '" borrowed successfully!');
        }

        return back()->with('error', 'Book is not available or you have already borrowed it.');
    }

    // Method to handle returning a book
    public function returnBook(Loan $loan)
    {
        // Ensure the loan belongs to the authenticated user and hasn't been returned
        if ($loan->user_id === Auth::id() && is_null($loan->returned_at)) {
            \DB::transaction(function () use ($loan) {
                $loan->update(['returned_at' => now()]);
                $loan->book->increment('available_copies');
            });

            return back()->with('success', 'Book "' . $loan->book->title . '" returned successfully!');
        }

        return back()->with('error', 'Could not return book.');
    }
}