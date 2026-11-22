<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        ContactMessage::query()->create($request->safe()->only([
            'name', 'email', 'subject', 'message',
        ]));

        return back()->with('success', __('ui.flash.contactSent'));
    }
}
