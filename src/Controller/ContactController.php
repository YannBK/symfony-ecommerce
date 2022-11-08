<?php

namespace App\Controller;

use DateTime;
use App\Entity\Contact;
use App\Entity\User;
use App\Classe\Mail;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Dotenv\Dotenv;

class ContactController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $dotenv = new Dotenv();
            $rootPath = $this->getParameter('kernel.project_dir');
            $dotenv->load($rootPath.'/.env');

            $formData = $form->getData();

            $subject = $formData['subject'];

            $content = "Message de :<br>".$formData['firstname']." ".$formData['lastname']."<br><br>";
            $content .= "Email de réponse :<br>".$formData['email']."<br><br>";
            $content .= "Message :<br>".$formData['content'];

            $mail = new Mail($_ENV['MAILJET_API_KEY'], $_ENV['MAILJET_API_KEY_SECRET']);
            $mail->send($_ENV['CONTACT_EMAIL'], "admin", "THEBoutik-".$subject, $content);

            $date = new DateTime();
            $contact = new Contact();
            $contact->setFirstname($formData['firstname']);
            $contact->setLastname($formData['lastname']);
            $contact->setEmail($formData['email']);
            $contact->setSubject($formData['subject']);
            $contact->setText($formData['content']);
            $contact->setCreatedAt($date);
            $contact->setAnswered(false);
            
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($formData['email']);
            
            if($user){
                $contact->setUser($user[0]);
            }
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Votre demande a été envoyée. Un administrateur vous répondra bientôt.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
