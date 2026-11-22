<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\SocialLink;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Setting::query()->pluck('value', 'key');
        $locale = app()->getLocale();

        return Inertia::render('Public/Home', [
            'title' => $settings["meta_title_{$locale}"] ?? ($settings['meta_title'] ?? null),
            'description' => $settings["meta_description_{$locale}"] ?? ($settings['meta_description'] ?? null),
            'profile' => Profile::query()->first(),
            'categories' => Category::query()
                ->withCount(['projects' => fn ($query) => $query->where('status', 'published')])
                ->orderBy('name')
                ->get(),
            'skills' => Skill::query()->orderBy('percentage', 'desc')->get(),
            'projects' => Project::query()
                ->published()
                ->with('category')
                ->orderByDesc('featured')
                ->orderByDesc('year')
                ->orderBy('id')
                ->get(),
        ]);
    }
}
