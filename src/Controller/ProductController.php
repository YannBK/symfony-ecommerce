<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Entity\Comment;
use App\Entity\Opinion;
// use App\Repository\OpinionRepository;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/nos-produits', name: 'app_products')]
    public function index(Request $request): Response
    {
        
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }
        else {
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }


    #[Route('/produit/{slug}', name: 'app_product')]
    public function show($slug): Response
    {
    //  produit
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        if(!$product) {
            return $this->redirectToRoute('app_products');
        }

    // commentaires
        $comments = $this->entityManager
            ->getRepository(Comment::class)
            ->findBy(array('product' => $product->getId()), array('createdAt' => 'DESC'));
        
        $time = new \DateTime('now');
        foreach ( $comments as $comment ) {

            // jours passés
            $days = $time->diff($comment->getCreatedAt());

            if($days->format('%a') === "0") {
                $timeNotif = $days->format('il y a %a jour');
            } else {
                $timeNotif = $days->format('il y a %a jours');
            }
            
            $comment->setDays($timeNotif);

            // opinions
            $opinions = $comment->getOpinions()->toArray();
            $positive = 0;
            $negative = 0;

            foreach( $opinions as $opinion ) {
                if($opinion->isOpinion() === true) {
                    $positive = $positive + 1;
                } else if($opinion->isOpinion() === false) {
                    $negative = $negative + 1;
                }
            }

            $comment->setLastOpinion(array('positive' => $positive, 'negative' => $negative));

            $this->entityManager->persist($comment);
        }

        $this->entityManager->flush();

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'comments' => $comments
        ]);
    }
}
