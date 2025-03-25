<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

final class EmailService
{
    private PHPMailer $mailer;
    public function __construct(
        private readonly string $mail,
        private readonly string $pswd,
        private readonly string $smtp,
        private readonly string $port,
        private readonly HttpClientInterface $httpClient
    ) {
        $this->mailer = new PHPMailer(true);
        $this->config();
    }

    public function config(): void
    {
        $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->smtp;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->mail;
        $this->mailer->Password = $this->pswd;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = (int) $this->port;

    }
    /**
     * Méthode pour envoyer des emails, duh
     * @param string $receiver
     * Qui reçoit le mail ?
     * @param string $subject
     * Sujet du mail
     * @param string $body
     * Que désirez-vous envoyer ? (contenu)
     * @return void
     */
    public function sendMail(string $receiver, string $subject, string $body): void
    {
        try {
            $this->mailer->setFrom($this->mail, 'Expéditeur');
            $this->mailer->addAddress($receiver, 'Utilisateur');

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            $this->mailer->send();
        } catch (Exception $e) {
            dd("Le message n'a pas été envoyé. Erreur: {$this->mailer->ErrorInfo}");
        }
    }
}