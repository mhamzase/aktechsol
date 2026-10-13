<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Settings\SiteSettings;
use Illuminate\Http\Request;

/**
 * PageController — legal pages (Privacy Policy, Terms & Conditions,
 * Policies) edited with the Quill rich text editor.
 * Stored in site settings; rendered on the public site.
 */
class PageController extends Controller
{
    /** Pages managed here: key => [title, settings field]. */
    public const PAGES = [
        'privacy' => ['title' => 'Privacy Policy', 'field' => 'privacy_policy_content'],
        'terms'   => ['title' => 'Terms & Conditions', 'field' => 'terms_conditions_content'],
        'policies'=> ['title' => 'Policies', 'field' => 'policies_content'],
    ];

    public function index(SiteSettings $settings)
    {
        $pages = collect(self::PAGES)->map(fn ($p, $key) => [
            'key' => $key,
            'title' => $p['title'],
            'updated' => $settings->updated_at ?? null,
            'has_content' => ! empty($settings->{$p['field']}),
        ]);

        return view('admin.pages.index', compact('pages'));
    }

    /** Drawer: edit form with Quill. */
    public function edit(SiteSettings $settings, string $page)
    {
        abort_unless(isset(self::PAGES[$page]), 404);

        return view('admin.pages._form', [
            'page' => $page,
            'title' => self::PAGES[$page]['title'],
            'content' => $settings->{self::PAGES[$page]['field']},
            'action' => route('admin.pages.update', $page),
        ]);
    }

    public function update(Request $request, SiteSettings $settings, string $page)
    {
        abort_unless(isset(self::PAGES[$page]), 404);

        $data = $request->validate([
            'content' => 'nullable|string',
        ]);

        $settings->{self::PAGES[$page]['field']} = $data['content'];
        $settings->save();

        ActivityLog::record('page.updated', null, self::PAGES[$page]['title'] . ' updated.');

        return response()->json(['message' => self::PAGES[$page]['title'] . ' saved.']);
    }
}
