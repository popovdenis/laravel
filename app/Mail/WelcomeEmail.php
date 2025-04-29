<?php
declare(strict_types=1);

namespace App\Mail;

use Illuminate\Support\Arr;
use MailerSend\Helpers\Builder\Personalization;

/**
 * Class WelcomeEmail
 *
 * @package App\Mail
 */
class WelcomeEmail extends MailSender
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
    ) {}

    public function getTemplateId(): string
    {
        return '3zxk54vvjoz4jy6v';
    }

    public function getPersonalization(): array
    {
        $to = Arr::get($this->to, '0.address');

        return [
            new Personalization($to, [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'email' => $this->email,
                'password' => $this->password,
            ]),
        ];
    }
}
