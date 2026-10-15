@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <h2 class="text-2xl font-bold text-slate-900 mb-1">Reset password</h2>
    <p class="text-sm text-slate-500 mb-8">Enter your email and we'll send you a reset link.</p>

    @if (session('status'))
        <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email address</label>
            <div class="relative">
                <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-3.5 7.158"/></svg>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com"
                       class="login-input block w-full rounded-xl border border-slate-300 bg-white pl-11 pr-4 py-3 text-sm text-slate-900 placeholder-slate-400">
            </div>
        </div>
        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-5 py-3 rounded-xl bg-[#1d4ed8] text-white font-bold text-sm hover:bg-[#1669d9] focus:outline-none focus:ring-2 focus:ring-[#1d4ed8] focus:ring-offset-2 transition shadow-lg shadow-blue-600/25">
            Send Reset Link
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-[#1d4ed8] hover:text-[#12307e]">&larr; Back to login</a>
        </div>
    </form>
@endsection
