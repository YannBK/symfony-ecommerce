<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Header;
use App\Entity\Marketing;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $best = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $products = array_slice($best, 0, 3);
        
        $headers = $this->entityManager->getRepository(Header::class)->findAll();
        
        $marketings = $this->entityManager->getRepository(Marketing::class)->findAllOrdered();

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'headers' => $headers,
            'marketings' => $marketings,
        ]);
    }
}
