<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = ContactMessage::query()
            ->when($request->string('status')->toString() === 'unread', fn ($query) => $query->where('is_read', false))
            ->when($request->string('status')->toString() === 'read', fn ($query) => $query->where('is_read', true))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return Inertia::render('Admin/Messages/Index', [
            'title' => 'Messages — Admin',
            'messages' => $messages,
            'filters' => $request->only(['status']),
        ]);
    }

    public function update(ContactMessage $contact_message)
    {
        $contact_message->update(['is_read' => ! $contact_message->is_read]);

        return back()->with('success', __('ui.flash.messageStatusUpdated'));
    }

    public function destroy(ContactMessage $contact_message)
    {
        $contact_message->delete();

        return back()->with('success', __('ui.flash.messageDeleted'));
    }
}
