<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

class MeetingCreated extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    public function __construct(
        public string $name,
        public string $meetingTime,
        public string $joinUrl,
    ) {}

    public function build(): self
    {
        $to = Arr::get($this->to, '0.address');

        return $this
            ->from('noreply@test-69oxl5e2jy2l785k.mlsender.net', 'English Course')
            ->view('emails.blank')
            ->mailersend(
                template_id: 'pq3enl66vx7l2vwr',
                personalization: [
                    new Personalization($to, [
                        'name' => $this->name,
                        'meeting_time' => $this->meetingTime,
                        'join_url' => $this->joinUrl,
                    ]),
                ],
            );
    }
}
