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
        // Initialisation du transport SMTP via DSN
        $transport = Transport::fromDsn('smtp://hopsorepau@cy-tech.fr:untfycnsrfuzqdqe@smtp.gmail.com:587');
        $this->mailer = new Mailer($transport);

        $this->fromAddress = $fromAddress;
    }

    public function sendEmail(string $recipient, string $subject, string $body): void
    {
        // Créer et configurer l'email
        $email = (new Email())
            ->from($this->fromAddress)
            ->to($recipient)
            ->subject($subject)
            ->text($body);

        try {
            // Envoyer l'email
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Gérer les erreurs d'envoi
            throw new \RuntimeException("Erreur lors de l'envoi de l'email : {$e->getMessage()}");
        }
    }
}
