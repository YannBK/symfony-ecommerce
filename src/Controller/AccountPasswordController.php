<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/password', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notif = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $old_psw = $form->get('old_password')->getData();
            if($encoder->isPasswordValid($user, $old_psw)) {
                $new_psw = $form->get('new_password')->getData();
                $password = $encoder->hashPassword($user, $new_psw);

                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $notif = "Le mot de passe a été mis à jour";
            } else {
                $notif = "Votre mot de passe actuel n'est pas le bon";
            }
        }

        return $this->render('account/password.html.twig', [
            'form'=> $form->createView(),
            'notif' => $notif
        ]);
    }
}
