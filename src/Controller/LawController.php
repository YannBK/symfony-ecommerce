<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LawController extends AbstractController
{
    #[Route('/law', name: 'app_law')]
    public function index(): Response
    {
        return $this->render('law/index.html.twig', [
            'controller_name' => 'LawController',
        ]);
    }
}
