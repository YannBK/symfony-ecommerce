<?php

    namespace App\EventSubscriber;

    use App\Entity\User;
    use App\Entity\Comment;
    use App\Entity\Header;
    use App\Entity\Marketing;
    use App\Entity\Product;
    use App\Entity\Opinion;
    use App\Entity\Order;
    use App\Entity\Note;
    use Doctrine\ORM\EntityManagerInterface;
    use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
    use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
    use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $entityManager;
    private $passwordEncoder;
    private $projectDir;

    public function __construct(EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordEncoder, string $projectDir)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->projectDir = $projectDir;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['addUser'],
            BeforeEntityDeletedEvent::class => ['deleteUser'],
            AfterEntityDeletedEvent::class => ['deletePhysicalImage'],
        ];
    }

    public function addUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }
        $this->setPassword($entity);
    }

    public function setPassword(User $entity): void
    {
        $pass = $entity->getPassword();

        $entity->setPassword(
            $this->passwordEncoder->hashPassword(
                $entity,
                $pass
            )
        );
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function deleteUser(BeforeEntityDeletedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $anonyme = $this->entityManager->getRepository(User::class)
        ->findOneBy(array('id' => 8888));

        $comments = $this->entityManager->getRepository(Comment::class)
        ->findBy(array('user' => $entity));

        foreach($comments as $comment) {
          $comment->setUser($anonyme);
          $this->entityManager->persist($comment);
        }

        $notes = $this->entityManager->getRepository(Note::class)
        ->findBy(array('user' => $entity));
        foreach($notes as $note) {
          $note->setUser($anonyme);
          $this->entityManager->persist($note);
        }

        $opinions = $this->entityManager->getRepository(Opinion::class)
        ->findBy(array('user' => $entity));
        foreach($opinions as $opinion) {
          $opinion->setUser($anonyme);
          $this->entityManager->persist($opinion);
          dump($opinion);
        }

        $orders = $this->entityManager->getRepository(Order::class)
        ->findBy(array('user' => $entity));
        foreach($orders as $order) {
          $order->setUser($anonyme);
          $this->entityManager->persist($order);
        }
        $this->entityManager->flush();
    }

    public function deletePhysicalImage(AfterEntityDeletedEvent $event){
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Product) && !($entity instanceof Header) && !($entity instanceof Marketing)) {
            return;
        }
        $imgName = $entity->getIllustration();
        $imgpath = $this->projectDir.'/public/uploads/'.$imgName;

        if(file_exists($imgpath)) unlink($imgpath);
    }
}