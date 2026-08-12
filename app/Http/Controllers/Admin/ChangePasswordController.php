<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function show()
    {
        return view('admin.change-password');
    }

    public function update(ChangePasswordRequest $request)
    {
        auth()->user()->update([
            'password' => Hash::make($request->new_password),
        ]);
        return redirect()->route('admin.change-password')->with('success', 'Password changed successfully.');
    }
}
