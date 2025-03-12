<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');
        $page = $request->input('page', 1);

        $books = Book::when(
            $title,
            fn($query, $title) => $query->title($title)
        );

        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withAvgRating()->withReviewsCount()
        };

        $cacheKey = 'books:' . $filter . ':' . $title . ':page' . $page;
        $books = cache()->remember(
            $cacheKey,
            3600,
            fn() => $books->paginate(10)
        );

        return view('books.index', ['books' => $books]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $cacheKey = 'book:' . $id;

        $book = cache()->remember(
            $cacheKey,
            3600,
            fn() => Book::withAvgRating()->withReviewsCount()->findOrFail($id)
        );

        $reviews = $book->reviews()->latest()->paginate(5);

        return view('books.show', [
            'book' => $book,
            'reviews' => $reviews
        ]);
    }

    public function destroy(Book $book, Request $request)
    {
        try {
            $filter = $request->input('filter', '');
            $title = $request->input('title', '');
            $page = $request->input('page', 1);
            $cacheKey = 'books:' . $filter . ':' . $title . ':page' . $page;

            cache()->forget('book:' . $book->id);
            cache()->forget($cacheKey);

            $book->delete();

            return redirect()->route('books.index', ['filter' => $filter, 'title' => $title])
                ->with('success', 'Book deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('books.index', ['filter' => $filter, 'title' => $title])
                ->with('error', 'Failed to delete this book.');
        }
    }
}
