<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Product;
use App\Form\Comment1Type;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

// #[Route('/comment')]
class CommentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // #[Route('/', name: 'app_comment_index', methods: ['GET'])]
    // public function index(CommentRepository $commentRepository): Response
    // {
    //     return $this->render('comment/index.html.twig', [
    //         'comments' => $commentRepository->findAll(),
    //     ]);
    // }

    #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(Comment1Type::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->add($comment, true);

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_comment_show', methods: ['GET'])]
    // public function show(Comment $comment): Response
    // {
    //     return $this->render('comment/show.html.twig', [
    //         'comment' => $comment,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment, CommentRepository $commentRepository): Response
    {
        $form = $this->createForm(Comment1Type::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->add($comment, true);

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }






    #[Route('/compte/mes-commandes/comment/delete/{commentId}/{productId}', name: 'app_comment_delete')]
    public function delete(CommentRepository $commentRepository, string $commentId, string $productId): Response
    {
        $myComment = $this->entityManager->getRepository(Comment::class)->findOneById($commentId);

        // if ($this->isCsrfTokenValid('delete'.$myComment->getId(), $request->request->get('_token'))) 
        // {
            // }
        $commentRepository->remove($myComment, true);

        return $this->redirectToRoute('app_account_evaluate_product', ['id' => $productId, 'update' => 'false'], Response::HTTP_SEE_OTHER);
    }
}
