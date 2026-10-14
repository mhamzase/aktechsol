<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

/**
 * AdminNotification — database notification for platform events.
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
class AdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
