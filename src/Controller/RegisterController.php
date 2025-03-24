<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hash,
        private readonly UserRepository $userRepository,
        private readonly ValidatorInterface $validatorInterface
    ) {
    }

    #[Route('/register', name: 'app_register')]
    public function addUser(
        Request $request,
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $msg = "";
        $type = "";

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $errors = $this->validatorInterface->validate($user);
            if ($errors->count() > 0) {
                echo $errors;
                return new Response($errors[0]->getMessage());
            }
            if (!$this->userRepository->findOneBy(["email" => $user->getEmail()])) {
                $user->setRoles(["ROLE_USER"]);
                $this->em->persist($user);
                $this->em->flush();
                $msg = "Le compte a été ajouté en BDD";
                $type = "success";
            } else {
                $msg = "Les informations email et ou password existe déja";
                $type = "danger";
            }
            $this->addFlash($type, $msg);
        }

        return $this->render('register/index.html.twig', [
            'form' => $form
        ]);
    }

}