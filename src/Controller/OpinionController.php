<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Opinion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class OpinionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/opinion/{commentId}/{productSlug}/{choice}', name: 'app_opinion')]
    public function positiveOpinion(string $commentId, string $productSlug, string $choice): Response
    {
        $user = $this->getUser();
        if($user === null) {
            $this->addFlash('notice', 'Veuillez vous connecter pour laisser un avis');
            return $this->redirectToRoute('app_login');
        }

        $comment = $this->entityManager->getRepository(Comment::class)->findOneBy(array('id'=>$commentId));
        
        $author = $comment->getUser();

        if($user === $author) {
            $this->addFlash('notice', 'Vous ne pouvez pas noter vos propres commentaires.');
            return $this->redirectToRoute('app_product', ['slug' => $productSlug]);
        }

        $opinion = $this->entityManager->getRepository(Opinion::class)->findOneBy(array('comment'=>$comment, 'user'=>$user));

        $choice === "true" ? $booleanChoice = 1 : $booleanChoice = 0;

        if($opinion) {
            $opinion->setOpinion($booleanChoice);
            $this->entityManager->persist($opinion);
        } else {
            $newOpinion = new Opinion();
            $newOpinion->setUser($user);
            $newOpinion->setComment($comment);
            $newOpinion->setOpinion($booleanChoice);
            $this->entityManager->persist($newOpinion);
        }
        $this->entityManager->flush();

        return $this->redirectToRoute('app_product', ['slug' => $productSlug]);
    }
}
