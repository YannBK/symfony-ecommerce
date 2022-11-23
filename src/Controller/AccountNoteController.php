<?php

namespace App\Controller;

use App\Entity\Note;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\NoteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AccountNoteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function averageNoteToStars($avg)
    {
        $stars = round($avg['average'], 0, PHP_ROUND_HALF_DOWN);
        $dec = $avg['average']-$stars;
        $dec > 0.7 ? $stars++ : 
            ($dec > 0.3 ? $halfStar = 1 : $halfStar = 0);

        return array('average' => $avg['average'], 'stars' => $stars, 'halfStar' => $halfStar);
    }

    #[Route('/compte/mes-commandes/note/{id}', name: 'app_account_note')]
    public function index(string $id, Request $request): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(array('id' => $id));
        $user = $this->getUser();
        $avg = $this->entityManager->getRepository(Note::class)->averageNoteForStars($product);
        $stars = $this->averageNoteToStars($avg);
        
        $existingNote = null;
        $checkNote = $this->entityManager
        ->getRepository(Note::class)
        ->findOneBy(array('user' => $user, 'product' => $product));
        if($checkNote) {
            $existingNote = $checkNote;
            $noteForm = $this->createForm(NoteType::class, $existingNote);
        } else {
            $noteForm = $this->createForm(NoteType::class);
        }

        $noteForm->handleRequest($request);
        
        if($noteForm->isSubmitted() && $noteForm->isValid()) {
            $note = $noteForm->getData();

            if(!$existingNote) {
                $note->setCreatedAt(new \DateTime('now'));
                $note->setUser($user);
                $note->setProduct($product);
            }

            $this->entityManager->persist($note);
            $this->entityManager->flush();

            $newAvg = $this->entityManager->getRepository(Note::class)->averageNoteForStars($product);
            $newStars = $this->averageNoteToStars($newAvg);

            $product->setAverageNote($newStars['average']);
            $product->setStars($newStars['stars']);
            $product->setHalfStar($newStars['halfStar']);

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_account_note', array('id' => $id));
        }


        return $this->render('account/note.html.twig', [
            'product' => $product,
            'noteForm' => $noteForm->createView(),
            'existingNote' => $existingNote,
            'stars' => $stars['stars'],
        ]);
    }
}
