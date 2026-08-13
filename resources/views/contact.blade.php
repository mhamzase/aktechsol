@extends('layouts.app')

@section('title', 'Contact Us')
@section('meta_description', 'Get in touch with AK Tech SOL.')

@section('content')
<x-frontend.page-banner
    title="Contact Us"
    subtitle="Have a project in mind? We'd love to hear from you."
/>
<section class="py-20 bg-white">
    <div class="max-w-3xl mx-auto px-4">

        @if(session('success'))
            <div class="mt-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('contact.store') }}" method="POST" class="mt-10 bg-gray-50 rounded-xl p-6 border border-gray-100 space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                    @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone (optional)</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('subject') border-red-500 @enderror">
                    @error('subject')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                <textarea name="message" id="message" rows="5" required
                          class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                @error('message')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
