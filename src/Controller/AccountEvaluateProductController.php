<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\CommentType;
use App\Form\UpdateCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AccountEvaluateProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/mes-commandes/comment/{id}/{update}', name: 'app_account_evaluate_product')]
    public function index(string $id, string $update, Request $request): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(array('id' => $id));
        $user = $this->getUser();
        
        $commentForm = $this->createForm(CommentType::class);
        $commentForm->handleRequest($request);
        
        // vérifier que l'user n'a pas déjà commenté le produit
        $existingComment = null;
        $checkComment = $this->entityManager
            ->getRepository(Comment::class)
            ->findOneBy(array('user' => $user, 'product' => $product));
        if($checkComment) {
            $existingComment = $checkComment;
        }
        
        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment = $commentForm->getData();
            
            $comment->setCreatedAt(new \DateTimeImmutable('now'));
            $comment->setUser($user);
            $comment->setProduct($product);
            
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_account_evaluate_product', array('id' => $id, 'update' => $update));
        }

        return $this->render('account/evaluate.html.twig', [
            'product' => $product,
            'commentForm' => $commentForm->createView(),
            'existingComment' => $existingComment,
            'update' => $update,
        ]);
    }

    #[Route('/compte/mes-commandes/comment/update/{id}/{update}', name: 'app_account_update_comment')]
    public function updateComment(string $id, string $update, Request $request): Response
    {
        // produit
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(array('id' => $id));
        // utilisateur
        $user = $this->getUser();
        // création et écoute du formulaire
        $commentForm = $this->createForm(UpdateCommentType::class);
        $commentForm->handleRequest($request);
        // ancien commentaire
        $existingComment = null;
        $checkComment = $this->entityManager
        ->getRepository(Comment::class)
        ->findOneBy(array('user' => $user, 'product' => $product));
        if($checkComment) {
            $existingComment = $checkComment;
        }
        
        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment = $commentForm->getData();
            $existingComment->setText($comment->getText());

            $this->entityManager->persist($existingComment);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_account_evaluate_product', array('id' => $product->getId(), 'update' => 'false'));
        }

        return $this->render('account/evaluate.html.twig', [
            'product' => $product,
            'commentForm' => $commentForm->createView(),
            'existingComment' => $existingComment,
            'update' => $update,
        ]);
    }

    // #[Route('/compte/mes-commandes/comment/delete/{id}', name: 'app_account_delete_comment')]
    // public function deleteComment(string $id)
    // {

    // }
}



//! version qui marche, juste pb fonction update et create dans la même fonction !
// class AccountEvaluateProductController extends AbstractController
// {
//     private $entityManager;

//     public function __construct(EntityManagerInterface $entityManager)
//     {
//         $this->entityManager = $entityManager;
//     }

//     #[Route('/compte/mes-commandes/comment/{id}/{update}', name: 'app_account_evaluate_product')]
//     public function index($id, $update, Request $request): Response
//     {
//         $product = $this->entityManager->getRepository(Product::class)->findOneBy(array('id' => $id));
//         // dd($id);
//         $user = $this->getUser();
// // dd($product);
//         $commentForm = $this->createForm(CommentType::class);
//         $commentForm->handleRequest($request);

//         // // TODO vérifier que l'user n'a pas déjà commenté le produit
//         $existingComment = null;
//         $checkComment = $this->entityManager
//             ->getRepository(Comment::class)
//             ->findOneBy(array('user' => $user, 'product' => $product));
        
//         if($checkComment) {
//             $existingComment = $checkComment;
//         }
//         //     //TODO faire un update
//         //     $this->addFlash('notice', 'Vous avez déjà laissé un avis sur ce produit.');
//         //     $commentForm->setData()
//         // } else {
//             if($commentForm->isSubmitted() && $commentForm->isValid()) {
//                 $comment = $commentForm->getData();
    
//                 $comment->setCreatedAt(new \DateTimeImmutable('now'));
//                 $comment->setUser($user);
//                 $comment->setProduct($product);
    
//                 $this->entityManager->persist($comment);
//                 $this->entityManager->flush();
//                 // dump($comment);
//                 // dd($user);
//             }
//         // }


//         return $this->render('account/evaluate.html.twig', [
//             'product' => $product,
//             'commentForm' => $commentForm->createView(),
//             'existingComment' => $existingComment,
//             'update' => $update,
//         ]);
//     }

//     #[Route('/compte/mes-commandes/comment/update/{id}', name: 'app_account_update_comment')]
//     public function updateComment($id)
//     {
//         echo('jojojojojojo');
//     }

//     #[Route('/compte/mes-commandes/comment/delete/{id}', name: 'app_account_delete_comment')]
//     public function deleteComment($id)
//     {

//     }
// }
