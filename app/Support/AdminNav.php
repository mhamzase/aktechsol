<?php

namespace App\Support;

use App\Models\ChatbotQuery;
use App\Models\ClientProject;
use App\Models\ContactMessage;
use App\Models\DemoBooking;
use App\Models\Invoice;
use App\Models\JobApplication;
use App\Models\Proposal;
use App\Models\QuoteRequest;

/**
 * AdminNav — sidebar/tab badge counts + topbar notification data.
 * Results are computed once per request and cached statically.
 * Wrapped in try/catch so the admin never white-screens when a
 * table is missing (e.g. before `migrate:fresh --seed`).
 */
class AdminNav
{
    protected static ?array $badges = null;

    /** Count badges used across sidebar, tabs and topbar. */
    public static function badges(): array
    {
        if (self::$badges !== null) {
            return self::$badges;
        }

        $zero = [
            'quotes' => 0, 'proposals' => 0, 'projects' => 0,
            'invoices' => 0, 'chatbot' => 0, 'demos' => 0,
            'contact' => 0, 'jobs' => 0, 'notifications' => 0,
        ];

        try {
            $zero['quotes']       = QuoteRequest::where('status', 'new')->count();
            $zero['proposals']    = Proposal::where('status', 'sent')->count();
            $zero['projects']     = ClientProject::whereIn('status', ['received', 'in_progress', 'review', 'revision'])->count();
            $zero['invoices']     = Invoice::whereIn('status', ['unpaid', 'partially_paid', 'overdue'])->count();
            $zero['chatbot']      = ChatbotQuery::whereNull('answer')->count();
            $zero['demos']        = DemoBooking::where('status', 'new')->count();
            $zero['contact']      = ContactMessage::where('status', 'new')->count();
            $zero['jobs']         = JobApplication::where('status', 'new')->count();
            $zero['notifications'] = auth()->check()
                ? auth()->user()->unreadNotifications()->count()
                : 0;
        } catch (\Throwable $e) {
            // Tables not migrated yet — show zeros instead of crashing.
        }

        return self::$badges = $zero;
    }

    /** Latest unread notifications for the topbar bell dropdown. */
    public static function latestNotifications(int $limit = 6)
    {
        try {
            return auth()->check()
                ? auth()->user()->unreadNotifications()->latest()->take($limit)->get()
                : collect();
        } catch (\Throwable $e) {
            return collect();
        }
    }
}
