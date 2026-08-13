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

        $mediaItems = $query->paginate(12)->withQueryString();

        return view('admin.media.index', compact('mediaItems'));
    }

    public function destroy(Media $media)
    {
        $media->delete();

        return redirect()->route('admin.media.index')
                         ->with('success', 'Media deleted successfully.');
    }
}
