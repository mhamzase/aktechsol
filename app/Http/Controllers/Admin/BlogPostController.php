<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with('category')->latest('published_at');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($catQuery) use ($search) {
                        $catQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status === 'active');
        }

        $posts = $query->paginate($request->get('per_page', 10));

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.blog-posts._rows', ['posts' => $posts])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $posts])->render(),
                'stats'      => "Showing {$posts->firstItem()}–{$posts->lastItem()} of {$posts->total()}",
            ]);
        }

        return view('admin.blog-posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog-posts._form', [
            'blogPost' => new BlogPost(),
            'categories' => BlogCategory::orderBy('name')->get(),
            'featuredUrl' => null,
            'action' => route('admin.blog-posts.store'),
            'method' => 'POST',
            'tableId' => 'blog-posts-table',
            'formTitle' => 'Add Post',
        ]);
    }

    public function store(StoreBlogPostRequest $request)
    {
        $post = BlogPost::create($request->validated());

        if ($request->hasFile('featured_image')) {
            $post->addMediaFromRequest('featured_image')
                ->toMediaCollection('featured_image');
        }

        if (request()->ajax()) {
            return response()->json(['message' => 'Blog post created successfully.']);
        }

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blogPost)
    {
        return view('admin.blog-posts._form', [
            'blogPost' => $blogPost,
            'categories' => BlogCategory::orderBy('name')->get(),
            'featuredUrl' => $blogPost->getFeaturedImageUrl(),
            'action' => route('admin.blog-posts.update', $blogPost),
            'method' => 'PUT',
            'tableId' => 'blog-posts-table',
            'formTitle' => 'Edit Post',
        ]);
    }

    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost)
    {
        $blogPost->fill($request->validated())->save();

        if ($request->hasFile('featured_image')) {
            $blogPost->clearMediaCollection('featured_image');
            $blogPost->addMediaFromRequest('featured_image')
                ->toMediaCollection('featured_image');
        }

        if (request()->ajax()) {
            return response()->json(['message' => 'Blog post updated successfully.']);
        }

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        $blogPost->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Blog post deleted successfully.']);
        }

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}
