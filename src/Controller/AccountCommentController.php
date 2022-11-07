<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AccountCommentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/mes-commandes/comment/{id}', name: 'app_account_comment')]
    public function index(string $id, Request $request, CommentRepository $commentRepository): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(array('id' => $id));
        $user = $this->getUser();
        
        $existingComment = null;
        $checkComment = $this->entityManager
            ->getRepository(Comment::class)
            ->findOneBy(array('user' => $user, 'product' => $product));

        if($checkComment) {
            $existingComment = $checkComment;
            $commentRepository->updateDays($existingComment, true);
            $commentForm = $this->createForm(CommentType::class, $existingComment);
        } else {
            $commentForm = $this->createForm(CommentType::class);
        }

        $commentForm->handleRequest($request);
        
        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment = $commentForm->getData();

            if(!$checkComment) {
                $comment->setCreatedAt(new \DateTime('now'));
                $comment->setUser($user);
                $comment->setProduct($product);
                $commentRepository->updateDays($comment, false);
            }
            
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_account_comment', ['id' => $id]);
        }

        return $this->render('account/comment.html.twig', [
            'product' => $product,
            'commentForm' => $commentForm->createView(),
            'existingComment' => $existingComment,
        ]);
    }

    #[Route('/compte/mes-commandes/comment/delete/{commentId}/{productId}', name: 'app_account_comment_delete')]
    public function delete(CommentRepository $commentRepository, string $commentId, string $productId): Response
    {
        $myComment = $this->entityManager->getRepository(Comment::class)->findOneById($commentId);

        $commentRepository->remove($myComment, true);

        return $this->redirectToRoute('app_account_comment', ['id' => $productId, 'update' => 'false'], Response::HTTP_SEE_OTHER);
    }
}