<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Services/Index', [
            'title' => 'Services — Admin',
            'services' => Service::query()->orderBy('id')->get(),
        ]);
    }

    public function store(ServiceRequest $request)
    {
        Service::query()->create($request->validated());

        return back()->with('success', __('ui.flash.serviceCreated'));
    }

    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        return back()->with('success', __('ui.flash.serviceUpdated'));
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return back()->with('success', __('ui.flash.serviceDeleted'));
    }
}
