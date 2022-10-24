<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }


    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_success')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if($order->getState() == 0) { 
            $cart->remove();
            $order->setState(1);
            $this->entityManager->flush();

            $mail = new Mail();
            $mailContent = "Bonjour ".$order->getUser()->getFirstname()."<br>Votre commande n° ".$order->getReference()." est bien validée!";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Commande validée', $mailContent);
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
