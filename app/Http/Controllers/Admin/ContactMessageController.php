<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactMessageStatusRequest;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query()->latest();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $messages = $query->paginate(10)->withQueryString();

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function updateStatus(UpdateContactMessageStatusRequest $request, ContactMessage $contactMessage)
    {
        $contactMessage->update(['status' => $request->validated('status')]);

        return redirect()->route('admin.contact-messages.show', $contactMessage)
                         ->with('success', 'Message status updated.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')
                         ->with('success', 'Contact message deleted.');
    }
}
