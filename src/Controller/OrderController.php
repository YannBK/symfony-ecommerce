<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart): Response
    {
        if(!$this->getUser()->getAddresses()->getValues()){
            return $this->redirectToRoute('app_account_address_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }
    
    #[Route('/commande/recapitulatif', name: 'app_order_recap', methods:"POST")]
    public function add(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            
            if($delivery->getCompany()){
                $delivery_content .= ' - '.$delivery->getCompany();
            }
            
            $delivery_content .= '</br></br>'.$delivery->getAddress();
            $delivery_content .= '</br>'.$delivery->getPostal();
            $delivery_content .= ' '.$delivery->getCity();
            $delivery_content .= '</br>'.$delivery->getCountry();

            $order = new Order();
            $order->setReference($date->format('dmY').'_'.uniqid());
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setState(0);

            $this->entityManager->persist($order);

            foreach($cart->getFull() as $prod) {
                $orderDetail = new OrderDetails();
                $orderDetail->setMyOrder(($order));
                $orderDetail->setProductName(($prod['product']->getName()));
                $orderDetail->setProduct(($prod['product']));
                $orderDetail->setQuantity(($prod['quantity']));
                $orderDetail->setPrice(($prod['product']->getPrice()));
                $orderDetail->setTotal(($prod['product']->getPrice() * $prod['quantity']));
                
                $this->entityManager->persist($orderDetail);
            }

            $this->entityManager->flush();

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $order->getReference()
            ]);
        }

        return $this->redirectToRoute(('app_cart'));
    }
}
