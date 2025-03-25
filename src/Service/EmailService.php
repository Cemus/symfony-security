<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class EmailService
{
    private string $dsn;
    private MailerInterface $mailer;

    // Injection de dépendance
    public function __construct(MailerInterface $mailer, string $dsn)
    {
        $this->mailer = $mailer;
        $this->dsn = $dsn;
    }

    public function sendEmail(): string
    {
        try {
            // Crée un transport à partir du DSN
            $transport = Transport::fromDsn($this->dsn);

            // Crée l'email
            $email = (new Email())
                ->from('coucoutest31@laposte.net')
                ->to('jeyajac188@birige.com')
                ->subject('Test Email Symfony')
                ->text('Ceci est un test d\'envoi d\'email avec Symfony.')
                ->html('<p>Ceci est un <strong>test</strong> d\'envoi d\'email avec Symfony.</p>');

            // Crée un objet Mailer avec le transport spécifique
            $mailer = new \Symfony\Component\Mailer\Mailer($transport);

            // Envoi de l'email de manière synchrone
            $mailer->send($email);

            return 'Email envoyé avec succès';
        } catch (\Exception $e) {
            return 'Erreur: ' . $e->getMessage();
        }
    }
}
