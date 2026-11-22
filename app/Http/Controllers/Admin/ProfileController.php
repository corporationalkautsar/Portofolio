<?php

namespace App\Http\Controllers\Admin;

use App\Http\Concerns\HandlesImages;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Inertia\Inertia;

class ProfileController extends Controller
{
    use HandlesImages;

    public function edit()
    {
        return Inertia::render('Admin/Profile/Edit', [
            'title' => 'Profile — Admin',
            'profile' => Profile::query()->first() ?? new Profile([
                'name' => 'Muhammad Iqbal Fadholi',
                'profession' => 'SISWA SMK REKAYASA PERANGKAT LUNAK (RPL)',
                'biography' => '',
                'biography_id' => '',
                'biography_en' => '',
                'email' => 'hello@example.com',
            ]),
        ]);
    }

    public function update(ProfileRequest $request)
    {
        $profile = Profile::query()->firstOrNew([]);
        $data = $request->validated();
        $data['photo'] = $this->replaceImage($request->file('photo'), $profile->photo, 'profile');

        $profile->fill($data)->save();

        return back()->with('success', __('ui.flash.profileSaved'));
    }
}
