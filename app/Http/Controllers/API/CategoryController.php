<?php

namespace App\Http\Controllers\API;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $categories = CategoryResource::collection($this->getAllCategories($request, 6))
            ->response()
            ->getData(true);
        return $this->wrapResponse(200, 'Success', $categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        $category = (new CategoryResource($category->load('books')))
            ->response()
            ->getData(true);
        return $this->wrapResponse(200, 'Success', $category);
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
     * query all categories by search
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $number (define paginate data per page)
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    private function getAllCategories(Request $request, int $number): LengthAwarePaginator
    {
        return Category::where('name', 'LIKE', "%$request->keyword%")
            ->latest()
            ->paginate($number)
            ->appends($request->query());
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
