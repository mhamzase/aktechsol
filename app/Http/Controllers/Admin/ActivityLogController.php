<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

/**
 * ActivityLogController — audit trail listing (AJAX data-table).
 */
class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate($request->get('per_page', 15));

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.activity-log._rows', compact('logs'))->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $logs])->render(),
                'stats'      => "Showing {$logs->firstItem()}–{$logs->lastItem()} of {$logs->total()}",
            ]);
        }

        return view('admin.activity-log.index');
    }
}
