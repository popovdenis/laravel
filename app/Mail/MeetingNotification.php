<?php
declare(strict_types=1);

namespace App\Mail;

use Illuminate\Support\Arr;
use MailerSend\Helpers\Builder\Personalization;

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

    public function getPersonalization(): array
    {
        $to = Arr::get($this->to, '0.address');

        return [
            new Personalization($to, [
                'name' => $this->name,
                'meeting_time' => $this->meetingTime,
                'join_url' => $this->joinUrl,
            ]),
        ];
    }
}
