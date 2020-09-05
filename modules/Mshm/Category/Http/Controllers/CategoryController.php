<?php

namespace Mshm\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Mshm\Category\Http\Requests\CategoryRequest;
use Mshm\Category\Models\Category;
use Mshm\Category\Repositories\CategoryRepo;
use Mshm\Common\Responses\AjaxResponses;

class CategoryController extends Controller
{
    public $repo;

    public function __construct(CategoryRepo $categoryRepo)
    {
        $this->repo = $categoryRepo;
    }

    public function index()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('manage', Category::class);
        $categories = $this->repo->all();
        return view('Categories::index', compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('manage', Category::class);
        $this->repo->store($request);
        return back();
    }

    public function edit($categoryId)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('manage', Category::class);
        $category = $this->repo->findById($categoryId);
        $categories = $this->repo->allExceptById($categoryId);
        return view('Categories::edit', compact('category', 'categories'));
    }

    public function update($categoryId, CategoryRequest $request)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('manage', Category::class);
        $this->repo->update($categoryId, $request);
        return back();
    }

    public function destroy($categoryId)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->authorize('manage', Category::class);
        $this->repo->delete($categoryId);
        AjaxResponses::successResponse();
    }

}
