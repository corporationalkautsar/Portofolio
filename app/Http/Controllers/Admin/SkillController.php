<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use Inertia\Inertia;

class SkillController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Skills/Index', [
            'title' => 'Skills — Admin',
            'skills' => Skill::query()->orderBy('percentage', 'desc')->get(),
        ]);
    }

    public function store(SkillRequest $request)
    {
        Skill::query()->create($request->validated());

        return back()->with('success', __('ui.flash.skillCreated'));
    }

    public function update(SkillRequest $request, Skill $skill)
    {
        $skill->update($request->validated());

        return back()->with('success', __('ui.flash.skillUpdated'));
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return back()->with('success', __('ui.flash.skillDeleted'));
    }
}
