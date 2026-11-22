<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Inertia\Inertia;

class WorkController extends Controller
{
    public function show(Project $project)
    {
        abort_if($project->status !== 'published', 404);

        $project->load('category');

        return Inertia::render('Public/WorkDetail', [
            'title' => $project->title.' — Selected Work',
            'description' => \Illuminate\Support\Str::limit($project->description, 155),
            'project' => $project,
            'related' => Project::query()
                ->published()
                ->where('category_id', $project->category_id)
                ->whereKeyNot($project->id)
                ->with('category')
                ->limit(3)
                ->get(),
        ]);
    }
}
