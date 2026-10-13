{{-- UI component kit demo — every x-ui.* component in one place for QA.
     Route: admin/ui-demo (not linked in sidebar, dev/QA only). --}}
@extends('admin.layouts.app')

@section('title', 'UI Components')

@section('content')
    <x-admin.page-header title="UI Components" subtitle="Shared form kit — x-ui.* — same theme on admin & frontend"
                        icon="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 8a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zm8 0a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1h-6a1 1 0 01-1-1v-6z" />

    <form action="{{ route('admin.ui-demo') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-4">Text inputs</h3>
            <x-ui.input name="demo_text" label="Text input" placeholder="Type something…" required
                        hint="This is a hint line." icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            <x-ui.input name="demo_email" type="email" label="Email input" placeholder="you@example.com" />
            <x-ui.password name="demo_password" label="Password" placeholder="••••••••" required />
            <x-ui.textarea name="demo_bio" label="Textarea" rows="3" placeholder="A few lines…" />
        </div>

        <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-4">Selects</h3>
            <x-ui.select name="demo_status" label="Single select" placeholder="Choose status…"
                         :options="['active' => 'Active', 'inactive' => 'Inactive']" value="active" />
            <x-ui.multi-select name="demo_tags[]" label="Multi select (searchable tags)"
                         :options="['web' => 'Web Development', 'design' => 'Graphic Design', 'seo' => 'SEO', 'content' => 'Content Writing', 'video' => 'Video Editing']"
                         :selected="['web', 'seo']" />
            <x-ui.radio name="demo_plan" label="Radio group" :options="['free' => 'Free', 'pro' => 'Pro', 'team' => 'Team']" value="pro" :inline="true" />
            <div class="flex flex-col gap-3 mt-2">
                <x-ui.checkbox name="demo_featured" label="Checkbox" description="A short description for this option." :checked="true" />
                <x-ui.toggle name="demo_active" label="Active / Inactive toggle" :checked="true" />
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-4">Dates</h3>
            <x-ui.date name="demo_date" label="Single date picker" hint="Custom calendar — not a native input." />
            <x-ui.date-range nameStart="demo_from" nameEnd="demo_to" label="Date range"
                             hint="Picking a start constrains the end date." />
            <x-ui.file name="demo_image" label="File upload" accept="image/*" hint="Live preview appears below." />
        </div>

        <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-4">Rich text</h3>
            <x-ui.rich-text name="demo_content" label="Quill editor (local)" placeholder="Write formatted content…" />
        </div>

        <div class="lg:col-span-2 flex justify-end gap-3">
            <button type="reset" class="btn btn-secondary">Reset</button>
            <button type="submit" class="btn btn-primary">Submit (demo)</button>
        </div>
    </form>
@endsection
