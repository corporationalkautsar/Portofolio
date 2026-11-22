<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Categories/Index', [
            'title' => 'Categories — Admin',
            'categories' => Category::query()->withCount('projects')->orderBy('name')->get(),
        ]);
    }

    public function store(CategoryRequest $request)
    {
        Category::query()->create($request->validated());

        return back()->with('success', __('ui.flash.categoryCreated'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return back()->with('success', __('ui.flash.categoryUpdated'));
    }

    public function destroy(Category $category)
    {
        if ($category->projects()->exists()) {
            return back()->with('error', __('ui.flash.categoryInUse'));
        }

        $category->delete();

        return back()->with('success', __('ui.flash.categoryDeleted'));
    }
}
