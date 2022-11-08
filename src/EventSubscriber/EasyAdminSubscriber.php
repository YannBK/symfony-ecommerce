<?php

  namespace App\EventSubscriber;

  use App\Entity\User;
  use Doctrine\ORM\EntityManagerInterface;
  use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
  use Symfony\Component\EventDispatcher\EventSubscriberInterface;
  use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

  class EasyAdminSubscriber implements EventSubscriberInterface
  {

      private $entityManager;
      private $passwordEncoder;

      public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
      {
          $this->entityManager = $entityManager;
          $this->passwordEncoder = $passwordEncoder;
      }

      public static function getSubscribedEvents()
      {
          return [
              BeforeEntityPersistedEvent::class => ['addUser'],
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

      /**
       * @param User $entity
       */
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

  }