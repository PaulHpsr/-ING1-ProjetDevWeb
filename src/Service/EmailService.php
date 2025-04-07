<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private MailerInterface $mailer;
    private string $fromAddress;

    public function __construct(MailerInterface $mailer, string $fromAddress = 'hopsorepau@cy-tech.fr')
    {
        $this->mailer = $mailer;
        $this->fromAddress = $fromAddress;
    }

    public function sendEmail(string $recipient, string $subject, string $body): void
    {
        $email = (new Email())
            ->from($this->fromAddress)
            ->to($recipient)
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }
}
