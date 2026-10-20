<?php

namespace App\Support;

use App\Models\User;
use App\Settings\SiteSettings;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

/**
 * AdminMail — queue an email to the site's admin recipients.
 *
 * The recipient is the Settings "Notification Email" when one is set.
 * When it is empty, the email falls back to every Administrator user's
 * email (the same people who receive bell notifications), so admin
 * emails are never silently dropped just because the setting was
 * left blank.
 */
class AdminMail
{
    /** @return string[] Recipient email addresses (may be empty). */
    public static function recipients(): array
    {
        try {
            $notificationEmail = app(SiteSettings::class)->notification_email ?? null;
            if ($notificationEmail) {
                return [$notificationEmail];
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return User::role('Administrator')
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /** Queue a mailable to the admin recipients (no-op when there are none). */
    public static function queue(Mailable $mailable): void
    {
        $recipients = static::recipients();

        if ($recipients) {
            Mail::to($recipients)->queue($mailable);
        }
    }
}
