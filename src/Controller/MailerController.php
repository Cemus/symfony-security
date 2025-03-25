<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

final class MailerController extends AbstractController
{
    private EmailService $emailService;

    // Injection du service EmailService
    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    #[Route('/email', name: 'send_email')]
    public function sendEmail(): JsonResponse
    {
        $result = $this->emailService->sendEmail();

        return new JsonResponse(['message' => $result]);
    }
}
