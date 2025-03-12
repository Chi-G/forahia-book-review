@extends('layouts.app')

@section('content')

  <div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl">{{ $book->title }}</h1>
    <img src="{{ asset('images/forahia-logo.png') }}" alt="Forahia Book Review Logo" class="logo">
  </div>

  <div class="mb-4">
    <!-- Back button -->
    <a href="{{ route('books.index') }}" class="btn mb-4">Home</a> |
    <a href="{{ url()->previous() }}" class="btn mb-4">Back</a>
    <hr class="my-4">

    <div class="book-info">
      <div class="book-author mb-4 text-lg font-semibold">By {{ $book->author }}</div>
      <div class="book-rating flex items-center">
        <div class="mr-2 text-sm font-medium text-red-400">
          <x-star-rating :rating="$book->reviews_avg_rating" />
        </div>
        <span class="book-review-count text-sm text-gray-100">
          {{ $book->reviews_count }} Total {{ Str::plural('review', $book->reviews_count) }}
        </span>
      </div>
    </div>
  </div>

  <div class="mb-4">
    <a href="{{ route('books.reviews.create', $book) }}" class="bg-green-500 text-white rounded-md px-4 py-2 font-medium shadow-sm hover:bg-green-600 inline-block">
      Add a review!
    </a>
  </div>

  <div>
    <h2 class="mb-4 text-xl font-semibold">Reviews</h2>
    <ul>
      @forelse ($reviews as $review)
        <li class="book-item mb-4">
          <div>
            <div class="mb-2 flex items-center justify-between">
              <div class="font-semibold">
                <x-star-rating :rating="$review->rating" />
              </div>
              <div class="book-review-count">
                {{ $review->created_at->format('M j, Y') }}</div>
            </div>
            <p class="text-gray-700">{{ $review->review }}</p>

            <!-- Delete button -->
            <form method="POST" action="{{ route('reviews.destroy', [$book, $review]) }}" class="delete-review-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </div>
        </li>
      @empty
        <li class="mb-4">
          <div class="empty-book-item">
            <p class="empty-text text-lg font-semibold">No reviews yet</p>
          </div>
        </li>
      @endforelse
    </ul>

    <!-- Pagination Links for Reviews -->
    <div class="pagination mt-4">
        {{ $reviews->links('vendor.pagination.custom') }}
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForms = document.querySelectorAll('.delete-review-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                if (confirm('Are you sure you want to delete this review?')) {
                    form.submit();
                }
            });
        });
    });
  </script>
@endsection
