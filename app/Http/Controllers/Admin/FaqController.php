<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Http\Requests\Admin\UpdateFaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query()->orderBy('sort_order');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status === 'active');
        }

        $faqs = $query->paginate($request->get('per_page', 10));

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.faqs._rows', ['faqs' => $faqs])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $faqs])->render(),
                'stats'      => "Showing {$faqs->firstItem()}–{$faqs->lastItem()} of {$faqs->total()}",
            ]);
        }

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(StoreFaqRequest $request)
    {
        Faq::create($request->validated());

        return redirect()->route('admin.faqs.index')
                         ->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $faq->fill($request->validated())->save();

        return redirect()->route('admin.faqs.index')
                         ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'FAQ deleted successfully.']);
        }


        return redirect()->route('admin.faqs.index')
                         ->with('success', 'FAQ deleted successfully.');
    }
}
