<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $services = Service::active()->orderBy('sort_order')->get();
        $projects = Project::active()->orderBy('sort_order')->get();
        $posts = BlogPost::published()->latest('published_at')->get();

        $staticPages = [
            url('/'),
            url('/about'),
            url('/services'),
            url('/portfolio'),
            url('/blog'),
            url('/faqs'),
            url('/contact'),
            url('/privacy-policy'),
            url('/terms-conditions'),
        ];

        $content = '<?xml version="1.0" encoding="UTF-8"?>';
        $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static pages
        foreach ($staticPages as $url) {
            $content .= '<url><loc>' . $url . '</loc></url>';
        }

        // Services
        foreach ($services as $service) {
            $content .= '<url><loc>' . route('services.show', $service->slug) . '</loc></url>';
        }

        // Projects
        foreach ($projects as $project) {
            $content .= '<url><loc>' . route('projects.show', $project->slug) . '</loc></url>';
        }

        // Blog Posts
        foreach ($posts as $post) {
            $content .= '<url><loc>' . route('blog.show', $post->slug) . '</loc></url>';
        }

        $content .= '</urlset>';

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
