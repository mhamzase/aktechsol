{{-- Drawer form: mark one revenue share as paid, with the payment
     proof screenshot attached (stored in Spatie Media Library). --}}
<form data-ajax-form data-reload-table="projects-table" method="POST" enctype="multipart/form-data"
      action="{{ route('admin.client-projects.revenue.paid', [$project, $share]) }}"
      data-reopen-drawer="{{ route('admin.client-projects.show', $project) }}" data-reopen-title="Project Details">
    @csrf

    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-2xl p-4 mb-5">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Paying</p>
        <p class="font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $share->member_name }}@if($share->isCompany()) (Company)@endif</p>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
            {{ rtrim(rtrim(number_format((float) $share->percentage, 2), '0'), '.') }}% of {{ $project->title }} =
            <strong class="text-slate-800 dark:text-slate-100">{{ \App\Support\Money::format($share->amount, $project->currency) }}</strong>
        </p>
    </div>

    <div class="mb-5">
        <x-ui.file name="proof" label="Payment Proof (Screenshot)" accept="image/*" required
                   hint="Upload the payment screenshot (bank transfer, JazzCash, EasyPaisa, etc.). JPG, PNG or WebP, max 5MB. The share can only be marked paid with a proof attached." />
    </div>

    <x-admin.drawer-footer save-label="Mark as Paid" />
</form>
