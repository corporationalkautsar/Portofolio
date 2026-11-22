<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Settings/Index', [
            'title' => 'Settings — Admin',
            'settings' => Setting::query()->orderBy('key')->get(),
        ]);
    }

    public function store(SettingRequest $request)
    {
        Setting::query()->create($request->validated());

        return back()->with('success', __('ui.flash.settingCreated'));
    }

    public function update(SettingRequest $request, Setting $setting)
    {
        $setting->update($request->validated());

        return back()->with('success', __('ui.flash.settingUpdated'));
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();

        return back()->with('success', __('ui.flash.settingDeleted'));
    }
}
