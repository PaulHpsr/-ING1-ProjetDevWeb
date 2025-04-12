<?php

namespace App\Service;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailService
{
    private Mailer $mailer;
    private string $fromAddress;

    public function __construct(string $fromAddress = 'hopsorepau@cy-tech.fr')
    {
        
        $transport = Transport::fromDsn('smtp://hopsorepau@cy-tech.fr:untfycnsrfuzqdqe@smtp.gmail.com:587');
        $this->mailer = new Mailer($transport);

        $this->fromAddress = $fromAddress;
    }

    public function sendEmail(string $recipient, string $subject, string $body): void
    {
        
        $email = (new Email())
            ->from($this->fromAddress)
            ->to($recipient)
            ->subject($subject)
            ->text($body);

        try {
           
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            
            throw new \RuntimeException("Erreur lors de l'envoi de l'email : {$e->getMessage()}");
        }
    }
}
