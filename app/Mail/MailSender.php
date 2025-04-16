<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use MailerSend\Helpers\Builder\Variable;
use MailerSend\Helpers\Builder\Personalization;
use MailerSend\LaravelDriver\MailerSendTrait;

abstract class MailSender extends Mailable
{
    use Queueable, SerializesModels, MailerSendTrait;

    public function build(): self
    {
        $to = Arr::get($this->to, '0.address');

        return $this
            ->from('noreply@test-69oxl5e2jy2l785k.mlsender.net', 'English Course')
            ->view('emails.blank')
            ->mailersend(
                template_id: $this->getTemplateId(),
                personalization: [
                    new Personalization($to, [
                        'name' => $this->name,
                        'meeting_time' => $this->meetingTime,
                        'join_url' => $this->joinUrl,
                    ]),
                ],
            );
    }

    abstract public function getTemplateId(): string;
}
