<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CategoryRequest;
use App\Traits\ImageHandler;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    use ImageHandler;

    /**
     * constant of property names
     *
     * @var string
     */
    private const ROUTE_INDEX = 'categories.index', ROUTE_TRASH = 'categories.trash';

    /**
     * authorizing the category controller
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
        $categories = $this->getAllCategories($request->keyword, 10);
        return view('pages.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        $data = $this->mergeData($request);
        $successMessage = 'Category has been created';

        return $this->checkProcess(self::ROUTE_INDEX, $successMessage, function () use ($request, $data) {
            if (Category::create($data)) {
                $this->createImage($request, $data['image'], 'categories');
            } else {
                throw new \Exception('Failed to create category');
            }
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('pages.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('pages.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = $this->mergeData($request, $category->image, false);
        $successMessage = 'Category successfully updated';

        return $this->checkProcess(self::ROUTE_INDEX, $successMessage, function () use ($category, $data, $request) {
            if ($category->update($data)) {
                $this->createImage($request, $category->image, 'categories');
            } else {
                throw new \Exception('Failed to update category');
            }
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $slug)
    {
        $category = $this->getOneCategoryBySlug($slug);
        $this->authorize('delete', $category);

        $successMessage = 'Category successfully deleted';
        $failedMessage = 'Failed to delete category';

        return $this->checkProcess(self::ROUTE_INDEX, $successMessage, function () use ($category, $failedMessage) {
            if (!$category->update(['deleted_by' => auth()->id()])) throw new \Exception($failedMessage);
            if (!$category->delete()) throw new \Exception($failedMessage);
        }, true);
    }

    /**
     * Display a listing of the deleted resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function indexTrash(Request $request)
    {
        $categories = $this->getAllCategories($request->keyword, 10, true);
        return view('pages.categories.trash.index-trash', compact('categories'));
    }

    /**
     * Display the specified deleted resource.
     *
     * @param  \App\Category  $user
     * @return \Illuminate\Http\Response
     */
    public function showTrash(Category $category)
    {
        return view('pages.categories.trash.show-trash', compact('category'));
    }

    /**
     * restore the specified deleted resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Category $category)
    {
        $successMessage = 'Category successfully restored';
        $failedMessage = "Failed to restore category";

        return $this->checkProcess(self::ROUTE_TRASH, $successMessage, function () use ($category, $failedMessage) {
            if (!$category->update(['deleted_by' => null])) throw new \Exception($failedMessage);
            if (!$category->restore()) throw new \Exception($failedMessage);
        }, true);
    }

    /**
     * Remove the specified deleted resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete(Category $category)
    {
        $categoryImage = $category->image;
        $successMessage = 'Category successfully deleted permanently';

        return $this->checkProcess(self::ROUTE_TRASH, $successMessage, function () use ($category, $categoryImage) {
            if ($category->forceDelete()) {
                $this->deleteImage('categories', $categoryImage);
            } else {
                throw new \Exception('Failed to delete user');
            }
        });
    }

    /**
     * query all categories by search
     *
     * @param  string $keyword
     * @param  int $number (define paginate data per page)
     * @param  bool $onlyDeleted (only trashed when delete using soft delete)
     * @return \illuminate\Pagination\LengthAwarePaginator
     */
    private function getAllCategories(?string $keyword, int $number, ?bool $onlyDeleted = false)
    {
        $categories = Category::where('name', 'LIKE', "%$keyword%");

        if ($onlyDeleted) $categories->onlyTrashed();

        return $categories->latest()
            ->paginate($number);
    }

    /**
     * query a category by slug
     *
     * @param  string $slug
     * @return \App\User|null
     */
    private function getOneCategoryBySlug(?string $slug)
    {
        return Category::where('slug', $slug)
            ->firstOrFail()
            ->loadCount('books');
    }

    /**
     * merge data into an array
     *
     * @param  object $request
     * @param  string|null $categoryImage
     * @param  bool $store (merge data when store a new category or update existing category)
     * @return array
     */
    private function mergeData(object $request, ?string $categoryImage = null, ?bool $store = true)
    {
        $validatedData = $request->validated();

        if ($store) {
            $additionalData = [
                'created_by' => auth()->id(),
                'image' => $this->setImageFile($request, 'categories')
            ];
        } else {
            $additionalData = [
                'updated_by' => auth()->id(),
                'image' => $this->setImageFile($request, 'categories', $categoryImage)
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
     * @return \Illuminate\Http\RedirectResponse
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
