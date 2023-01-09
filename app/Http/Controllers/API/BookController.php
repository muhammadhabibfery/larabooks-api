<?php

namespace App\Http\Controllers\API;

use App\Book;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $books = BookResource::collection($this->getAllBooks($request, 6))
            ->response()
            ->getData(true);
        return $this->wrapResponse(200, 'Success', $books);
    }

    /**
     * Display a listing (top by view field) of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topBooks(): JsonResponse
    {
        $topBooks = BookResource::collection($this->getTopBooks(6))
            ->response()
            ->getData(true);
        return $this->wrapResponse(200, 'Success', $topBooks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Book $book): JsonResponse
    {
        if (!$this->updateViews($book)) throw new ModelNotFoundException('Book not found');
        $book = (new BookResource($book->load(['categories', 'city'])))
            ->response()
            ->getData(true);
        return $this->wrapResponse(200, 'Success', $book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * query all books by search
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $number (define paginate data per page)
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function getAllBooks(Request $request, int $number): LengthAwarePaginator
    {
        return Book::where('title', 'LIKE', "%$request->keyword%")
            ->latest()
            ->paginate($number)
            ->appends($request->query());
    }

    /**
     * query top books
     *
     * @param  int $number (define paginate data per page)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTopBooks(int $number): Collection
    {
        return Book::with(['categories', 'city'])
            ->where('views', '>', 0)
            ->orderBy('views', 'desc')
            ->limit($number)
            ->get();
    }

    /**
     * query to update a book's views
     *
     * @param  \App\Book $book
     * @return bool
     */
    private function updateViews(Book $book): bool
    {
        $book->views += 1;
        return $book->save();
    }

    /**
     * wrap a result into response json
     *
     * @param  int $code
     * @param  string $message
     * @param  array $resource
     * @return JsonResponse
     */
    private function wrapResponse(int $code, string $message, array $resource): JsonResponse
    {
        $result = ['code' => $code, 'message' => $message, 'data' => $resource['data']];

        if (count($resource) > 1)
            $result = array_merge($result, ['pages' => ['links' => $resource['links'], 'meta' => $resource['meta']]]);

        return response()->json($result, $code);
    }
}
