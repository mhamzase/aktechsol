<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'services'      => Service::count(),
            'projects'      => Project::count(),
            'blog_posts'    => BlogPost::count(),
            'testimonials'  => Testimonial::count(),
            'unread_messages' => ContactMessage::where('status', 'new')->count(),
        ];

        $recentMessages = ContactMessage::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentMessages'));
    }
}
