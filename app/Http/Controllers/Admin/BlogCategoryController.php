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
        return view('admin.blog-categories.create');
    }

    public function store(StoreBlogCategoryRequest $request)
    {
        BlogCategory::create($request->validated());

        return redirect()->route('admin.blog-categories.index')
                         ->with('success', 'Blog category created successfully.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        return view('admin.blog-categories.edit', compact('blogCategory'));
    }

    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blogCategory)
    {
        $blogCategory->fill($request->validated())->save();

        return redirect()->route('admin.blog-categories.index')
                         ->with('success', 'Blog category updated successfully.');
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
}
