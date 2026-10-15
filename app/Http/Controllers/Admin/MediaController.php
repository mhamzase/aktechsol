<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::query()->with('model')->latest();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                  ->orWhere('collection_name', 'like', "%{$search}%")
                  ->orWhere('model_type', 'like', "%{$search}%");
            });
        }

        $mediaItems = $query->paginate($request->get('per_page', 12));

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.media._rows', ['mediaItems' => $mediaItems])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $mediaItems])->render(),
                'stats'      => "Showing {$mediaItems->firstItem()}–{$mediaItems->lastItem()} of {$mediaItems->total()}",
            ]);
        }

        return view('admin.media.index', compact('mediaItems'));
    }

    /** Drawer: view media details. */
    public function show(Media $media)
    {
        $media->load('model');

        return view('admin.media.show', compact('media'));
    }

    public function destroy(Media $media)
    {
        $media->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Media deleted successfully.']);
        }


        return redirect()->route('admin.media.index')
                         ->with('success', 'Media deleted successfully.');
    }
}
