@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="hidden" name="email" value="{{ $request->email }}">

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input id="password" type="password" name="password" required
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <button type="submit" class="w-full inline-flex justify-center items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            Reset Password
        </button>
    </form>
@endsection
