<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
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
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        $msg = "";
        $type = "";

        if ($form->isSubmitted() && $form->isValid()) {
            try {

                $password = $form->get('password')->getData();
                $hashedPassword = $this->hash->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
                $user->setRoles(['ROLE_USER']);

                $msg = $user->getFirstname() . " a été ajouté avec succès !";
                $type = "success";
                $this->addFlash($type, $msg);

                $this->em->persist($user);
                $this->em->flush();

                var_dump($msg . $type);

                return $this->render('register/index.html.twig', [
                    'form' => $form->createView(),
                    'msg' => $msg,
                    'type' => $type,
                ]);
            } catch (\Exception $e) {
                return $this->render('register/index.html.twig', [
                    'form' => $form->createView(),
                    'msg' => $msg,
                    'type' => $type,
                ]);
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'msg' => $msg,
            'type' => $type,
        ]);
    }

    #[Route('/activate/{id}', name: 'app_redirect')]
    public function activate(int $id)
    {
        $user = $this->em->getRepository(User::class)->findBy(
            array('id' => $id)
        )[0];
        $user->setStatus(!$user->isStatus());
        $this->em->persist($user);
        $this->em->flush();
        return $this->render('activate/index.html.twig', [
            "user" => $user->getFirstname() . $user->getFirstname() . "(" . $user->getId() . ")",
            "status" => $user->isStatus() ? "ACTIF" : "INACTIF"
        ]);
    }
}