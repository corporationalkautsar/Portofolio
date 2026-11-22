<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialLinkRequest;
use App\Models\SocialLink;
use Inertia\Inertia;

class SocialLinkController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/SocialLinks/Index', [
            'title' => 'Social links — Admin',
            'socialLinks' => SocialLink::query()->orderBy('platform')->get(),
        ]);
    }

    public function store(SocialLinkRequest $request)
    {
        SocialLink::query()->create($request->validated());

        return back()->with('success', __('ui.flash.socialLinkCreated'));
    }

    public function update(SocialLinkRequest $request, SocialLink $social_link)
    {
        $social_link->update($request->validated());

        return back()->with('success', __('ui.flash.socialLinkUpdated'));
    }

    public function destroy(SocialLink $social_link)
    {
        $social_link->delete();

        return back()->with('success', __('ui.flash.socialLinkDeleted'));
    }
}
