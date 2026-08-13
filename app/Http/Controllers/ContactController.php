<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function store(ContactFormRequest $request)
    {
        ContactMessage::create($request->validated());

        return back()->with('success', 'Your message has been sent successfully.');
    }
}
