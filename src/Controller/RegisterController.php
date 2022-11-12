<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Dotenv\Dotenv;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if(!$search_email){
                $password = $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
    
                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $dotenv = new Dotenv();
                $rootPath = $this->getParameter('kernel.project_dir');
                $dotenv->load($rootPath.'/.env');

                $mail = new Mail($_ENV['MAILJET_API_KEY'], $_ENV['MAILJET_API_KEY_SECRET']);
                $mailContent = "Bonjour nouvel inscrit ".$user->getFirstname()."<br>Bienvenue!";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Arrivée à MossHeaven', $mailContent);

                $this->addFlash('notice', "Votre inscription s'est bien déroulée, vous pouvez vous connecter");

                return $this->redirectToRoute('app_login', array('error' => null,
                    'last_username' => null));
            } else {
                $this->addFlash('notice', "L'email utilisé existe déjà.");
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
