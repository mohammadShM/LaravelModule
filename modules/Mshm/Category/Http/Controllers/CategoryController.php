<?php

namespace Mshm\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Mshm\Category\Http\Requests\CategoryRequest;
use Mshm\Category\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        //TODO: categoryRepository
        $categories = Category::all();
        return view('Categories::index', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        //TODO: categoryRepository
        Category::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'parent_id' => $request->parent_id,
        ]);
        return back();
    }

    public function edit(Category $category)
    {
        //TODO: categoryRepository
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('Categories::edit', compact('category', 'categories'));
    }

    public function update(Category $category, CategoryRequest $request)
    {
        //TODO: categoryRepository
        $category->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'parent_id' => $request->parent_id,
        ]);
        return back();
    }

    public function destroy(Category $category)
    {
        //TODO: categoryRepository
        /** @noinspection PhpUnhandledExceptionInspection */
        $category->delete();
        return response()->json(['message' => 'عملیات با موفقیت انجام شد .'], Response::HTTP_OK);
    }

}
