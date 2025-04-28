<?php

namespace App\Listeners;

use App\Services\EmailNotificationService;
use Illuminate\Auth\Events\Registered;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     */
    public function __construct(protected EmailNotificationService $emailNotificationService)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        if (setting('smtp.enable') && setting('smtp.transport') === 'mail_sender') {
            $this->emailNotificationService->sendWelcomeEmail($event->user);
        }
    }
}
