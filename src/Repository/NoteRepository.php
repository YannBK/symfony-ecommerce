<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Note>
 *
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function add(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Note $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return [] Returns an array of stars to display
//     */
   public function averageNoteToStars($product): array
   {
       $avg =  $this->createQueryBuilder('n')
           ->andWhere('n.product = :product')
           ->setParameter('product', $product)
           ->select('AVG(n.note) as average')
           ->getQuery()
           ->getOneOrNullResult();
        
        $stars = round($avg['average'], 0, PHP_ROUND_HALF_DOWN);
        $dec = $avg['average']-$stars;
        $dec > 0.7 ? $stars++ : 
            ($dec > 0.3 ? $halfStar = 1 : $halfStar = 0);

        return array('stars' => $stars, 'halfStar' => $halfStar);
   }
}
