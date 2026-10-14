<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * AdminNotification — database notification for platform events.
 *
 * Delivered synchronously (simple DB insert) so admins see it immediately
 * without needing a queue worker.
 *
 * Usage:
 *   $admin->notify(new AdminNotification(
 *       type: 'quote',
 *       title: 'New quote request',
 *       message: 'Ali Raza requested a quote for Web Development.',
 *       url: route('admin.quotes.show', $quote),
 *   ));
 *
 * Stored in the `notifications` table (data: type/title/message/url).
 */
class AdminNotification extends Notification
{
    public function __construct(
        public string $type,
        public string $title,
        public string $message,
        public ?string $url = null,
    ) {}

    /** Deliver via the database channel only. */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** Payload stored in the notifications table. */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => $this->type,
            'title'   => $this->title,
            'message' => $this->message,
            'url'     => $this->url,
        ];
    }
}
