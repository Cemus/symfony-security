<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class EmailService
{
    public function __construct(private string $mail, private string $pswd, private string $smtp, private string $port, private readonly HttpClientInterface $httpClient)
    {
    }

    public function getMail(): string
    {
        return $this->mail;
    }

    public function getPswd(): string
    {
        return $this->pswd;
    }
    public function getSmtp(): string
    {
        return $this->smtp;
    }
    public function getPort(): string
    {
        return $this->port;
    }

    public function getVariables(): string
    {
        return $this->mail . "" . $this->pswd . "" . $this->smtp . "" . $this->port;
    }
}