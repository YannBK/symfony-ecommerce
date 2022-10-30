<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User; //import de l'entity User
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;//permet d'accéder au manager d'entités de doctrine
use Symfony\Component\HttpFoundation\Request; //permet d'acceder aux requêtes http
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; //pouvoir acceder aux fonctions de hash

// use Doctrine\Persistence\ManagerRegistry; 

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }


    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response //Request permet d'écouter les requêtes http
    {
        $notification = null;
        $user = new User(); //nouvelle instance de User
        $form = $this->createForm(RegisterType::class, $user); //création du formulaire

        $form->handleRequest($request); //on demande au formulaire d'être à l'écoute d'une requête ( de soumission)
        if($form->isSubmitted() && $form->isValid()) {//si le formulaire est submit && valide(par rapport à ce qui est défini dans RegisterType)

            $user = $form->getData();//on remplit l'instance $user avec les données du formulaire

            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());
            if(!$search_email){
                //dd($user); // <=> die(var_dump()) en mieux
                $password = $encoder->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
    
                $this->entityManager->persist($user);//on dit qu'on veut éventuellement sauver ces données (on les fige)
                $this->entityManager->flush();//on exécute la requête(insert)

                $mail = new Mail();
                $mailContent = "Bonjour nouvel inscrit ".$user->getFirstname()."<br>Bienvenue!";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur la boutique ultime', $mailContent);

                // $notification = "Votre inscription s'est bien déroulée, vous pouvez vous connecter";

                $this->addFlash('notice', "Votre inscription s'est bien déroulée, vous pouvez vous connecter");

                return $this->render('security/login.html.twig', [
                    // 'form' => $form->createView(),//insertion de la vue du formulaire
                    'error' => null,
                    'last_username' => null
                ]);
            } else {
                // $notification = "L'email utilisé existe déjà.";
                $this->addFlash('notice', "L'email utilisé existe déjà.");
            }

        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()//,
            //'notification' => $notification
        ]);
    }
}
