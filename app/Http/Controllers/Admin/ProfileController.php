<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', [
            'user' => auth()->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        auth()->user()->update($request->validated());
        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }
}
