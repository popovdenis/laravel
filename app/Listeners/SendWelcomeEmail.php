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
        if (setting('mailsender.use_mail_sender')) {
            $this->emailNotificationService->sendWelcomeEmail($event->user);
        }
    }
}
