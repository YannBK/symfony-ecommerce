<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Opinion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function add(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $opinions = $this->getEntityManager()->getRepository(Opinion::class)->findByComment($entity);
        
        foreach($opinions as $opinion) {
            $this->getEntityManager()->remove($opinion);
            $this->getEntityManager()->flush();
        }

        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function updateDays(Comment $entity, bool $flush = false): void
    {
        $time = new \DateTime('now');
        $days = $time->diff($entity->getCreatedAt());

        if($days->format('%a') === "0") {
            $timeNotif = $days->format('il y a %a jour');
        } else {
            $timeNotif = $days->format('il y a %a jours');
        }
            
        $entity->setDays($timeNotif);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function updateOpinions(Comment $entity, bool $flush = false): void
    {
        $opinions = $entity->getOpinions()->toArray();
        $positive = 0;
        $negative = 0;

        foreach( $opinions as $opinion ) {
            if($opinion->isOpinion() === true) {
                $positive = $positive + 1;
            } else if($opinion->isOpinion() === false) {
                $negative = $negative + 1;
            }
        }

        $entity->setLastOpinion(array('positive' => $positive, 'negative' => $negative));

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
