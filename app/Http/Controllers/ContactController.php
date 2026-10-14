<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactMessage;
use App\Support\NotifyAdmins;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function store(ContactFormRequest $request)
    {
        $message = ContactMessage::create($request->validated());

        NotifyAdmins::send(
            'contact',
            'New contact message',
            "{$message->name} sent a message: " . str($message->message)->limit(80),
            route('admin.contact-messages.show', $message),
        );

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
