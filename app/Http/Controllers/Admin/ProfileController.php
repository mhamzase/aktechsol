<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $avatarUrl = $user->getAvatarUrl();
        return view('admin.profile', compact('user', 'avatarUrl'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        $user->update($request->only('name', 'email'));

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatar');
            $user->addMediaFromRequest('avatar')
                 ->toMediaCollection('avatar');
        }

        // Optional: remove avatar if a separate remove field is present
        if ($request->input('remove_avatar') === '1') {
            $user->clearMediaCollection('avatar');
        }

        return redirect()->route('admin.profile')
                         ->with('success', 'Profile updated successfully.');
    }
}
