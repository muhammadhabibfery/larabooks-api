<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
    //         if (Gate::allows('manage-categories')) return $next($request);
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

        $categories = Category::where('name', 'LIKE', "%$keyword%")
            ->latest()
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data = array_merge(
            $request->validated(),
            [
                'created_by' => auth()->user()->id,
                'category_image' => uploadImage($request, 'category_images'),
            ]
        );

        Category::create($data);

        return redirect()
            ->route('categories.index')
            ->with('status', 'Category succesfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CategoryRequest  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $data = array_merge(
            $request->validated(),
            [
                'updated_by' => auth()->user()->id,
                'category_image' => uploadImage($request, 'category_images', $category->category_image),
            ]
        );

        $category->update($data);

        return redirect()
            ->route('categories.index')
            ->with('status', 'Category succesfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->update(['deleted_by' => auth()->user()->id]);
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('status', 'Category succesfully moved to trash');
    }

    public function trash(Request $request)
    {
        $keyword = $request->search;

        $deleted_categories = Category::onlyTrashed()
            ->where('name', 'LIKE', "%$keyword%")
            ->latest()
            ->paginate(10);

        return view('categories.trash', compact('deleted_categories'));
    }

    public function restore($slug)
    {
        $restore_category = Category::onlyTrashed()
            ->where('slug', $slug)
            ->firstOrFail();

        $restore_category->update(['deleted_by' => null]);
        $restore_category->restore();

        return redirect()
            ->route('categories.index')
            ->with('status', 'Category succesfully restored');
    }

    public function forceDelete($slug)
    {
        $delete_category = Category::onlyTrashed()
            ->where('slug', $slug)
            ->firstOrFail();

        if ($delete_category->category_image) {
            Storage::disk('public')
                ->delete($delete_category->category_image);
        }

        $delete_category->forceDelete();

        return redirect()
            ->route('categories.trash')
            ->with('status', 'Category permanently deleted');
    }
}
