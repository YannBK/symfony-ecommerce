<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Entity\Comment;
use App\Entity\Note;
use App\Repository\CommentRepository;
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
    public function show($slug, CommentRepository $commentRepository): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        if(!$product) {
            return $this->redirectToRoute('app_products');
        }

        $comments = $this->entityManager
            ->getRepository(Comment::class)
            ->findBy(array('product' => $product->getId()), array('createdAt' => 'DESC'));
        
        foreach ( $comments as $comment ) {
            $commentRepository->updateDays($comment, true);

            $commentRepository->updateOpinions($comment, true);
        }

        $note = $this->entityManager->getRepository(Note::class)->averageNoteToStars($product);

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'comments' => $comments, 
            'stars' => $note['stars'],
            'halfStar' => $note['halfStar']
        ]);
    }
}
