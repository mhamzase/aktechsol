<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactMessageMail;
use App\Models\ContactMessage;
use App\Settings\SiteSettings;
use App\Support\NotifyAdmins;
use Illuminate\Support\Facades\Mail;

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

        // Also email the admins (queued) so leads are not missed when the
        // panel is not open.
        $notifyEmail = app(SiteSettings::class)->notification_email ?? null;
        if ($notifyEmail) {
            Mail::to($notifyEmail)->queue(new ContactMessageMail($message));
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Your message has been sent successfully. We\'ll get back to you soon.']);
        }

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
