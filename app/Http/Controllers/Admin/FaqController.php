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
        return view('admin.faqs._form', [
            'faq' => new Faq(),
            'action' => route('admin.faqs.store'),
            'method' => 'POST',
            'tableId' => 'faqs-table',
            'formTitle' => 'Add FAQ',
        ]);
    }

    public function store(StoreFaqRequest $request)
    {
        Faq::create($request->validated());

        if (request()->ajax()) {
            return response()->json(['message' => 'FAQ created successfully.']);
        }

        return redirect()->route('admin.faqs.index')
                         ->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs._form', [
            'faq' => $faq,
            'action' => route('admin.faqs.update', $faq),
            'method' => 'PUT',
            'tableId' => 'faqs-table',
            'formTitle' => 'Edit FAQ',
        ]);
    }

    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $faq->fill($request->validated())->save();

        if (request()->ajax()) {
            return response()->json(['message' => 'FAQ updated successfully.']);
        }

        return redirect()->route('admin.faqs.index')
                         ->with('success', 'FAQ updated successfully.');
    }

    /** Drawer: view FAQ details. */
    public function show(Faq $faq)
    {
        return view('admin.faqs.show', compact('faq'));
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

    /** Toggle active/inactive status via AJAX. */
    public function toggleStatus(Faq $faq)
    {
        $faq->update(['status' => ! $faq->status]);

        return response()->json([
            'message' => $faq->status ? 'FAQ activated.' : 'FAQ deactivated.',
            'status' => (bool) $faq->status,
        ]);
    }
}
