<?php

namespace App\Controller;

use App\Entity\ResetPassword;
use App\Entity\User;
use App\Classe\Mail;
use App\Form\ResetPasswordType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Dotenv\Dotenv;

class ResetPasswordController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublie', name: 'app_reset_password')]
    public function index(Request $request): Response
    {
        //redirection home si déjà connecté
        if($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if($request->get('email')) {
            //récupérer un utilisateur par son email
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if($user) {
                // enregistrer en base la demande de reset password qui lie un utilisateur à un token et à une date
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                //envoie un mail à l'utilisateur avec un lien permettant de mettre à jour son mdp
                $url = $this->generateUrl("app_update_password", ['token' => $reset_password->getToken()]);

                $content = "Bonjour ".$user->getFirstname()." ".$user->getLastname()."<br><br>Vous avez demandé à réinitialiser votre mot de passe sur le site THE boutique.com<br><br>";
                $content .= "Merci de bien vouloir cliquer sur <a href='".$url."'>le lien suivant</a> pour mettre à jour votre mot de passe.";

                $dotenv = new Dotenv();
                $rootPath = $this->getParameter('kernel.project_dir');
                $dotenv->load($rootPath.'/.env');

                $mail = new Mail($_ENV['MAILJET_API_KEY'], $_ENV['MAILJET_API_KEY_SECRET']);
                $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(), "Réinitialisez votre mot de passe sur THE boutique", $content);

                $this->addFlash('notice', 'Vous allez recevoir un mail avec la procédure pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('notice', 'Votre adresse mail est inconnue');

            }
        }
        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'app_update_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $encoder)
    {
        //obtenir de la base l'enregistrement de la demande
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        //vérifier l'existence du token
        if(!$reset_password) {
            return $this->redirectToRoute('app_reset_password');
        }

        //vérifier l'expiration du token
        $now = new DateTime();
        if($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. <br>Merci de la renouveller');
            return $this->redirectToRoute('app_reset_password');
        }

        //Rendre une vue avec mot de passe + confirmer le mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $new_psw = $form->get('new_password')->getData();
            $password = $encoder->hashPassword($reset_password->getUser(), $new_psw);
            $reset_password->getUser()->setPassword($password);

            $this->entityManager->flush();

            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
