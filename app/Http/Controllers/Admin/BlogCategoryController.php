<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogCategoryRequest;
use App\Http\Requests\Admin\UpdateBlogCategoryRequest;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogCategory::query()->latest('sort_order');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status === 'active');
        }

        $categories = $query->paginate($request->get('per_page', 10));

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.blog-categories._rows', ['categories' => $categories])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $categories])->render(),
                'stats'      => "Showing {$categories->firstItem()}–{$categories->lastItem()} of {$categories->total()}",
            ]);
        }

        return view('admin.blog-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog-categories._form', [
            'blogCategory' => new BlogCategory(),
            'action' => route('admin.blog-categories.store'),
            'method' => 'POST',
            'tableId' => 'blog-categories-table',
            'formTitle' => 'Add Category',
        ]);
    }

    public function store(StoreBlogCategoryRequest $request)
    {
        BlogCategory::create($request->validated());

        if (request()->ajax()) {
            return response()->json(['message' => 'Blog category created successfully.']);
        }

        return redirect()->route('admin.blog-categories.index')
                         ->with('success', 'Blog category created successfully.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        return view('admin.blog-categories._form', [
            'blogCategory' => $blogCategory,
            'action' => route('admin.blog-categories.update', $blogCategory),
            'method' => 'PUT',
            'tableId' => 'blog-categories-table',
            'formTitle' => 'Edit Category',
        ]);
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory)
    {
        $blogCategory->fill($request->validated())->save();

        if (request()->ajax()) {
            return response()->json(['message' => 'Blog category updated successfully.']);
        }

        return redirect()->route('admin.blog-categories.index')
                         ->with('success', 'Blog category updated successfully.');
    }

    /** Drawer: view category details. */
    public function show(BlogCategory $blogCategory)
    {
        $blogCategory->loadCount('posts');

        return view('admin.blog-categories.show', ['category' => $blogCategory]);
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Blog category deleted successfully.']);
        }


        return redirect()->route('admin.blog-categories.index')
                         ->with('success', 'Blog category deleted successfully.');
    }

    /** Toggle active/inactive status via AJAX. */
    public function toggleStatus(BlogCategory $blogCategory)
    {
        $blogCategory->update(['status' => ! $blogCategory->status]);

        return response()->json([
            'message' => $blogCategory->status ? 'Category activated.' : 'Category deactivated.',
            'status' => (bool) $blogCategory->status,
        ]);
    }
}
