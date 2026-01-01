<?php

namespace App\Http\Controllers;

use App\Models\BookCategory;
use App\Models\Book;
use App\Models\Subject;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookStoreController extends Controller
{
    // Book store home
    public function index()
    {
        $featuredCategories = BookCategory::where('status', 'active')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        $newArrivals = Book::with(['product', 'category'])
            ->whereHas('product', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('created_at', 'desc')
            ->limit(12)
            ->get();

        $testTypes = TestType::where('status', 'active')
            ->withCount('subjects')
            ->orderBy('sort_order')
            ->get();

        return view('book-store.index', compact('featuredCategories', 'newArrivals', 'testTypes'));
    }

    // Browse books by category
    public function browseByCategory($slug = null)
    {
        $categories = BookCategory::where('status', 'active')
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        $selectedCategory = null;
        $books = collect();

        if ($slug) {
            $selectedCategory = BookCategory::where('slug', $slug)->firstOrFail();

            // Get all books in this category and subcategories
            $categoryIds = [$selectedCategory->id];
            $categoryIds = array_merge(
                $categoryIds,
                $selectedCategory->children->pluck('id')->toArray()
            );

            $books = Book::with(['product', 'category'])
                ->whereIn('book_category_id', $categoryIds)
                ->whereHas('product', function ($query) {
                    $query->where('is_active', true);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(24);
        }

        return view('book-store.browse-category', compact('categories', 'selectedCategory', 'books'));
    }

    // Browse books by test type and subject
    public function browseBySubject($typeSlug, $subjectSlug = null)
    {
        $testType = TestType::where('slug', $typeSlug)->firstOrFail();
        $subjects = Subject::where('test_type_id', $testType->id)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        $selectedSubject = null;
        $books = collect();

        if ($subjectSlug) {
            $selectedSubject = Subject::where('slug', $subjectSlug)
                ->where('test_type_id', $testType->id)
                ->firstOrFail();

            $books = Book::with(['product', 'category'])
                ->where('subject', $selectedSubject->name)
                ->whereHas('product', function ($query) {
                    $query->where('is_active', true);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(24);
        } else {
            $books = Book::with(['product', 'category'])
                ->whereHas('product', function ($query) use ($testType) {
                    $query->where('is_active', true);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(24);
        }

        return view('book-store.browse-subject', compact('testType', 'subjects', 'selectedSubject', 'books'));
    }

    // Show book details
    public function showBook($slug)
    {
        $book = Book::with(['product.shop', 'category', 'product.reviews'])
            ->whereHas('product', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->firstOrFail();

        $relatedBooks = Book::with(['product'])
            ->where('id', '!=', $book->id)
            ->where(function ($query) use ($book) {
                $query->where('subject', $book->subject)
                    ->orWhere('book_category_id', $book->book_category_id)
                    ->orWhere('author', $book->author);
            })
            ->whereHas('product', function ($query) {
                $query->where('is_active', true);
            })
            ->limit(6)
            ->get();

        return view('book-store.book-details', compact('book', 'relatedBooks'));
    }

    // Search books
    public function searchBooks(Request $request)
    {
        $query = Book::with(['product.shop', 'category'])
            ->whereHas('product', function ($query) {
                $query->where('is_active', true);
            });

        if ($request->has('q') && $request->q) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('author', 'like', '%' . $searchTerm . '%')
                    ->orWhere('publisher', 'like', '%' . $searchTerm . '%')
                    ->orWhere('subject', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('product', function ($productQuery) use ($searchTerm) {
                        $productQuery->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('book_category_id', $request->category_id);
        }

        if ($request->has('subject') && $request->subject) {
            $query->where('subject', $request->subject);
        }

        if ($request->has('education_level') && $request->education_level) {
            $query->where('education_level', $request->education_level);
        }

        if ($request->has('board') && $request->board) {
            $query->where('education_board', $request->board);
        }

        if ($request->has('condition') && $request->condition) {
            $query->where('book_condition', $request->condition);
        }

        if ($request->has('min_price') && $request->min_price) {
            $query->whereHas('product', function ($productQuery) use ($request) {
                $productQuery->where('base_price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->whereHas('product', function ($productQuery) use ($request) {
                $productQuery->where('base_price', '<=', $request->max_price);
            });
        }

        $books = $query->orderBy('created_at', 'desc')->paginate(24);

        $categories = BookCategory::where('status', 'active')->get();
        $subjects = Subject::where('status', 'active')->get();

        return view('book-store.search-books', compact('books', 'categories', 'subjects'));
    }

    // Sell a book form
    public function sellBookForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please login to sell books.');
        }

        $categories = BookCategory::where('status', 'active')
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        $subjects = Subject::where('status', 'active')->get();
        $testTypes = TestType::where('status', 'active')->get();

        return view('book-store.sell-book', compact('categories', 'subjects', 'testTypes'));
    }

    // Submit book for sale
    public function submitBookForSale(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'edition' => 'required|string|max:50',
            'isbn' => 'nullable|string|unique:books,isbn',
            'category_id' => 'required|exists:book_categories,id',
            'subject' => 'nullable|string',
            'education_level' => 'nullable|string',
            'board' => 'nullable|string',
            'condition' => 'required|in:new,like_new,good,fair,poor',
            'condition_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048'
        ]);

        // Here you would create the product and book records
        // This is a simplified version

        return redirect()->route('book-store.my-listings')
            ->with('success', 'Book submitted successfully. It will be reviewed by our team.');
    }

    // User's book listings
    public function myListings()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $books = Book::with(['product'])
            ->whereHas('product', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('book-store.my-listings', compact('books'));
    }

    // Books by condition
    public function booksByCondition($condition)
    {
        $validConditions = ['new', 'like_new', 'good', 'fair', 'poor'];

        if (!in_array($condition, $validConditions)) {
            abort(404);
        }

        $books = Book::with(['product.shop', 'category'])
            ->where('book_condition', $condition)
            ->whereHas('product', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        $conditionLabels = [
            'new' => 'New Books',
            'like_new' => 'Like New Books',
            'good' => 'Good Condition Books',
            'fair' => 'Fair Condition Books',
            'poor' => 'Poor Condition Books'
        ];

        return view('book-store.books-by-condition', compact('books', 'condition', 'conditionLabels'));
    }
}
