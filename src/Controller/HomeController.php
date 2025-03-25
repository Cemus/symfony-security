<?php

namespace App\Controller;

use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct()
    {

    }
    #[Route('/', name: 'app_home')]
    public function index(EmailService $emailService): Response
    {
        $emailService->sendMail("jeyajac188@birige.com", "Je veux voir si ça marche", "Ça marche !");
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
