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

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Your message has been sent successfully. We\'ll get back to you soon.']);
        }

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
