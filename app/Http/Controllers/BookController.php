<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Builder;

class BookController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         if (Gate::allows('manage-books')) return $next($request);
    //         abort('403');
    //     });
    // }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->search;
        $status = $request->action;
        $books = $this->getBooksFromQueryString($keyword, $status);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::latest()
            ->get();

        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\BookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BookRequest $request)
    {
        $data = array_merge(
            $request->validated(),
            [
                'status' => $request->action,
                'created_by' => auth()->user()->id,
                'cover' => uploadImage($request, 'covers'),
            ]
        );

        $book = Book::create($data);

        $this->attachPivotTable($request->categories, $book);

        $status  = ($request->status == 'PUBLISH') ? 'Book succesfully saved and published' : 'Book saved as draft';

        return redirect()->route('books.index')
            ->with('status', $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Book $book)
    {
        $categories = Category::latest()
            ->get();

        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\BookRequest  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(BookRequest $request, Book $book)
    {
        $data = array_merge(
            $request->validated(),
            [
                'status' => $request->action,
                'updated_by' => auth()->user()->id,
                'cover' => uploadImage($request, 'covers', $book->cover),
            ]
        );

        $book->update($data);

        $this->syncPivotTable($request->categories, $book);

        return redirect()->route('books.index')
            ->with('status', 'Book successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        $book->update(['deleted_by' => auth()->user()->id]);
        $book->delete();
        return redirect()->route('books.index')
            ->with('status', 'Book moved to trash');
    }

    /**
     * Display a listing of the resource (soft delete) in trash.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request)
    {
        $status = $request->action;
        $keyword = $request->search;
        $deleted_books = $this->getBooksFromQueryString($keyword, $status, true);
        return view('books.trash', compact('deleted_books'));
    }

    public function restore($slug)
    {
        $restore_book = Book::onlyTrashed()
            ->where('slug', $slug)
            ->firstOrFail();

        $restore_book->update(['deleted_by' => null]);
        $restore_book->restore();

        return redirect()->route('books.trash')
            ->with('status', 'Book succesfully restored');
    }

    public function forceDelete($slug)
    {
        $delete_book = Book::onlyTrashed()
            ->where('slug', $slug)
            ->firstOrFail();

        if ($delete_book->cover) {
            Storage::disk('public')
                ->delete($delete_book->cover);
        }

        $delete_book->categories()
            ->detach();

        $delete_book->forceDelete();

        return redirect()->route('books.trash')
            ->with('status', 'Book permanently deleted');
    }

    /**
     * attachPivotTable
     *
     * @param  mixed $data
     * @param  mixed $model
     * @return void
     */
    private function attachPivotTable($data, $model)
    {
        if ($data) {
            try {
                foreach ($data as $categories) {

                    $decrypted_categories[] = decrypt($categories);
                }

                $model->categories()
                    ->attach($decrypted_categories);
            } catch (DecryptException $e) {
                return abort(404);
            }
        }
    }

    private function syncPivotTable($data, $model)
    {
        if ($data) {
            try {
                foreach ($data as $categories) {

                    $decrypted_categories[] = decrypt($categories);
                }

                $model->categories()
                    ->sync($decrypted_categories);
            } catch (DecryptException $e) {
                return abort(404);
            }
        } else {
            if ($model->categories->isNotEmpty()) $model->categories()->detach();
        }
    }

    private function getBooksFromQueryString($keyword, $status, $trashed = false)
    {
        $status = strtoupper($status);

        if (!$trashed) {
            $data = Book::with(['categories'])
                ->where('status', 'LIKE', "%$status%")
                ->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%$keyword%")
                        ->orWhere('author', 'LIKE', "%$keyword%")
                        ->orWhere('publisher', 'LIKE', "%$keyword%")
                        ->orWhereHas('categories', function (Builder $q) use ($keyword) {
                            $q->where('name', 'LIKE', "%$keyword%");
                        });
                })
                ->latest()
                ->paginate(10);
        } else {
            $data = Book::onlyTrashed()
                ->with(['categories'])
                ->where('status', 'LIKE', "%$status%")
                ->where(function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', "%$keyword%")
                        ->orWhere('author', 'LIKE', "%$keyword%")
                        ->orWhere('publisher', 'LIKE', "%$keyword%")
                        ->orWhereHas('categories', function (Builder $q) use ($keyword) {
                            $q->where('name', 'LIKE', "%$keyword%");
                        });
                })
                ->latest()
                ->paginate(10);
        }

        return $data;
    }
}
