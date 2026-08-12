@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>
        <button type="submit" class="w-full inline-flex justify-center items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
            Send Password Reset Link
        </button>
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800">&larr; Back to login</a>
        </div>
    </form>
@endsection
