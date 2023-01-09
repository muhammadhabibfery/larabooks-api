<?php

namespace App\Http\Controllers;

use App\Book;
use App\City;
use App\Category;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Http\Requests\BookRequest;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    use ImageHandler;

    /**
     * constant of property names
     *
     * @var string
     */
    private const ROUTE_INDEX = 'books.index', ROUTE_TRASH = 'books.trash', STATUS = ['PUBLISH', 'DRAFT'];

    /**
     * authorizing the book controller
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('authRole:ADMIN')->only(['indexTrash', 'showTrash', 'restore', 'forceDelete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = self::STATUS;
        $books = $this->getAllBooksBySearch($request->keyword, $request->status, 10);
        return view('pages.books.index', compact('books', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->getAllCategories();
        $status = self::STATUS;
        $cities = $this->getAllCities();
        return view('pages.books.create', compact('categories', 'status', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BookRequest  $request
     * @return mixed
     */
    public function store(BookRequest $request)
    {
        $data = $this->mergeData($request);
        $successMessage = 'Book succesfully created';

        return $this->checkProcess(self::ROUTE_INDEX, $successMessage, function () use ($request, $data) {
            if ($book = Book::create($data)) {
                $book->categories()->attach($data['categories']);
                $this->createImage($request, $book->cover, 'books');
            } else {
                throw new \Exception('Failed to create book');
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return view('pages.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $categories = $this->getAllCategories();
        $status = self::STATUS;
        return view('pages.books.edit', compact('book', 'categories', 'status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\BookRequest  $request
     * @param  \App\Book  $book
     * @return mixed
     */
    public function update(BookRequest $request, Book $book)
    {
        $data = $this->mergeData($request, $book->cover, false);
        $successMessage = 'Book successfully updated';

        return $this->checkProcess(self::ROUTE_INDEX, $successMessage, function () use ($request, $data, $book) {
            if ($book->update($data)) {
                $book->categories()->sync($data['categories']);
                $this->createImage($request, $book->cover, 'books');
            } else {
                throw new \Exception('Failed to update book');
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return mixed
     */
    public function destroy(Book $book)
    {
        $successMessage = 'Book successfully deleted';
        $failedMessage = 'Failed to delete book';

        return $this->checkProcess(self::ROUTE_INDEX, $successMessage, function () use ($book, $failedMessage) {
            if (!$book->update(['deleted_by' => auth()->id()])) throw new \Exception($failedMessage);
            if (!$book->delete()) throw new \Exception($failedMessage);
        }, true);
    }

    /**
     * Display a listing of the deleted resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function indextrash(Request $request)
    {
        $status = self::STATUS;
        $books = $this->getAllBooksBySearch($request->keyword, $request->status, 10, true);
        return view('pages.books.trash.index-trash', compact('books', 'status'));
    }

    /**
     * Display the specified deleted resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function showTrash(Book $book)
    {
        return view('pages.books.trash.show-trash', compact('book'));
    }

    /**
     * restore the specified deleted resource.
     *
     * @param  \App\Book  $book
     * @return mixed
     */
    public function restore(Book $book)
    {
        $successMessage = 'Book successfully restored';
        $failedMessage = 'Failed to restore book';

        return $this->checkProcess(self::ROUTE_TRASH, $successMessage, function () use ($book, $failedMessage) {
            if (!$book->update(['deleted_by' => null])) throw new \Exception($failedMessage);
            if (!$book->restore()) throw new \Exception($failedMessage);
        }, true);
    }

    /**
     * remove the specified deleted resource.
     *
     * @param  \App\Book  $book
     * @return mixed
     */
    public function forceDelete(Book $book)
    {
        $bookCover = $book->cover;
        $successMessage = 'Book successfully deleted permanently';
        $failedMessage = 'Failed to delete book permanently';

        return $this->checkProcess(self::ROUTE_TRASH, $successMessage, function () use ($book, $bookCover, $failedMessage) {
            if ($book->categories()->detach() > 0) {
                if ($book->forceDelete()) {
                    $this->deleteImage('books', $bookCover);
                } else {
                    throw new \Exception($failedMessage);
                }
            } else {
                throw new \Exception($failedMessage);
            }
        }, true);
    }

    /**
     * query all books by search
     *
     * @param  string $keyword
     * @param  string $status
     * @param  int $number (define paginate data per page)
     * @param  bool $onlyDeleted (only trashed when delete using soft delete)
     * @return \illuminate\Pagination\LengthAwarePaginator
     */
    private function getAllBooksBySearch(?string $keyword, ?string $status, int $number, bool $onlyDeleted = false)
    {
        $books = Book::with(['categories' => fn ($query) => $query->where('name', 'LIKE', "%$keyword%")])
            ->where(fn ($query) => $query->getCategories($keyword)->orWhere('title', 'LIKE', "%$keyword%"));

        if ($status && in_array($status, self::STATUS)) $books->where('status', $status);

        if ($onlyDeleted) $books->onlyTrashed();

        return $books->latest()
            ->paginate($number);
    }

    /**
     * query all categories
     *
     * @return \App\Category
     */
    private function getAllCategories()
    {
        return Category::latest()->get();
    }

    /**
     * query all cities
     *
     * @return \App\City
     */
    private function getAllCities()
    {
        return City::latest()->get();
    }

    /**
     * merge data into an array
     *
     * @param  object $request
     * @param  string|null $bookCover
     * @param  bool $store (merge data when store a new book or update existing book)
     * @return array
     */
    private function mergeData(object $request, ?string $bookCover = null, ?bool $store = true)
    {
        $validatedData = $request->validated();

        if ($store) {
            $additionalData = [
                'created_by' => auth()->id(),
                'cover' => $this->setImageFile($request, 'books')
            ];
        } else {
            $additionalData = [
                'updated_by' => auth()->id(),
                'cover' => $this->setImageFile($request, 'books', $bookCover)
            ];
        }

        return array_merge($validatedData, $additionalData);
    }

    /**
     * Check one or more processes and catch them if fail
     *
     * @param  string $routeName
     * @param  string $successMessage
     * @param  callable $action
     * @param  bool $dbTransaction (use database transaction for multiple queries)
     * @return \Illuminate\Http\Response
     */
    private function checkProcess(string $routeName, string $successMessage, callable $action, ?bool $dbTransaction = false)
    {
        try {
            if ($dbTransaction) DB::beginTransaction();

            $action();

            if ($dbTransaction) DB::commit();
        } catch (\Exception $e) {
            if ($dbTransaction) DB::rollback();

            return redirect()->route($routeName)
                ->with('failed', $e->getMessage());
        }

        return redirect()->route($routeName)
            ->with('success', $successMessage);
    }
}
