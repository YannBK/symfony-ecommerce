<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Dotenv\Dotenv;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'app_stripe_create_session')]
    public function index(EntityManagerInterface $entityManager, $reference)
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        // $YOUR_DOMAIN = 'https://mossheaven.test-bettk.eu';

        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if(!$order){
            return($this->redirect('/commande'));
        };

        foreach($order->getOrderDetails()->getValues() as $prod) {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($prod->getProductName());

            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $prod->getPrice(),
                    'product_data' => [
                        'name' => $prod->getProductName(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                    ],
                ],
                'quantity' => $prod->getQuantity(),
            ];
        }
        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        $dotenv = new Dotenv();
        $rootPath = $this->getParameter('kernel.project_dir');
        $dotenv->load($rootPath.'/.env');

        Stripe::setApiKey($_ENV['STRIPE_API_KEY']);
        
        $checkout_session = Session::create([
            'customer_email' => $this->getUser()->getEmail(),
            'line_items' => [[
                $products_for_stripe
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManager->flush();

        return($this->redirect($checkout_session->url));
    }
}
