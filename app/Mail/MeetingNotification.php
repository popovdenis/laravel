<?php
declare(strict_types=1);

namespace App\Mail;

/**
 * Class MeetingNotification
 *
 * @package App\Mail
 */
class MeetingNotification extends MailSender
{
    public function __construct(
        public string $name,
        public string $meetingTime,
        public string $joinUrl,
    ) {}

    public function getTemplateId(): string
    {
        return 'pq3enl66vx7l2vwr';
    }
}
