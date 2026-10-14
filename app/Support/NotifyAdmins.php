<?php

namespace App\Support;

use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;

/**
 * NotifyAdmins — send a database notification to every Administrator.
 *
 * Usage:
 *   NotifyAdmins::send('quote', 'New quote request', 'Ali requested…', $url);
 */
class NotifyAdmins
{
    public static function send(string $type, string $title, string $message, ?string $url = null): void
    {
        try {
            $admins = User::role('Administrator')->get();
            if ($admins->isEmpty()) {
                return;
            }
            Notification::send($admins, new AdminNotification($type, $title, $message, $url));
        } catch (\Throwable $e) {
            // Never break the public flow because a notification failed.
            report($e);
        }
    }
}
