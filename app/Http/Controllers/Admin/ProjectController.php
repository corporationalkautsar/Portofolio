<?php

namespace App\Http\Controllers\Admin;

use App\Http\Concerns\HandlesImages;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    use HandlesImages;

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'status']);

        $projects = Project::query()
            ->with('category')
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($request->integer('category'), fn ($query, $id) => $query->where('category_id', $id))
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Projects/Index', [
            'title' => 'Projects — Admin',
            'projects' => $projects,
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Projects/Form', [
            'title' => 'New Project — Admin',
            'project' => null,
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(ProjectRequest $request)
    {
        $data = $request->validated();
        $data['featured'] = $request->boolean('featured');
        $data['thumbnail'] = $this->storeImage($request->file('thumbnail'), 'projects');
        $data['project_image'] = $this->storeImage($request->file('project_image'), 'projects');

        Project::query()->create($data);

        return redirect()->route('admin.projects.index')->with('success', __('ui.flash.projectCreated'));
    }

    public function edit(Project $project)
    {
        return Inertia::render('Admin/Projects/Form', [
            'title' => 'Edit Project — Admin',
            'project' => $project,
            'categories' => Category::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $data = $request->validated();
        $data['featured'] = $request->boolean('featured');
        $data['thumbnail'] = $this->replaceImage($request->file('thumbnail'), $project->thumbnail, 'projects');
        $data['project_image'] = $this->replaceImage($request->file('project_image'), $project->project_image, 'projects');

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', __('ui.flash.projectUpdated'));
    }

    public function destroy(Project $project)
    {
        $this->deleteImage($project->thumbnail);
        $this->deleteImage($project->project_image);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', __('ui.flash.projectDeleted'));
    }
}
