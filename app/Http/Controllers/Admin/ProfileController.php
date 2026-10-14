<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $avatarUrl = $user->getAvatarUrl();
        return view('admin.profile', compact('user', 'avatarUrl'));
    }

    /**
     * Update name/email. Returns JSON for AJAX, redirect otherwise.
     * (Avatar is handled by the dedicated avatar() endpoint.)
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        $user->update($request->only('name', 'email'));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'initial' => strtoupper(substr($user->name, 0, 1)),
                ],
            ]);
        }

        return redirect()->route('admin.profile')
                         ->with('success', 'Profile updated successfully.');
    }

    /**
     * AJAX avatar upload / removal (drag & drop zone).
     * Upload: multipart `avatar` (image, max 2MB). Remove: `remove=1`.
     */
    public function avatar(Request $request)
    {
        $user = auth()->user();

        if ($request->input('remove') === '1') {
            $user->clearMediaCollection('avatar');
            return response()->json([
                'success' => true,
                'avatar_url' => null,
                'initial' => strtoupper(substr($user->name, 0, 1)),
                'message' => 'Profile photo removed.',
            ]);
        }

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        $user->clearMediaCollection('avatar');
        $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');

        return response()->json([
            'success' => true,
            'avatar_url' => $user->getAvatarUrl(),
            'message' => 'Profile photo updated.',
        ]);
    }
}
