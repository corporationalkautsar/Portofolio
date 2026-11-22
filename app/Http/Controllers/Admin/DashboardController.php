<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'title' => 'Dashboard — Admin',
            'stats' => [
                'projects' => Project::count(),
                'published' => Project::where('status', 'published')->count(),
                'drafts' => Project::where('status', 'draft')->count(),
                'unread' => ContactMessage::where('is_read', false)->count(),
                'messages' => ContactMessage::count(),
                'categories' => Category::count(),
                'skills' => Skill::count(),
                'services' => Service::count(),
            ],
            'recentProjects' => Project::query()->with('category')->latest()->limit(5)->get(),
            'recentMessages' => ContactMessage::query()->latest()->limit(5)->get(),
            'byCategory' => Category::query()->withCount('projects')->orderByDesc('projects_count')->get(),
        ]);
    }
}
