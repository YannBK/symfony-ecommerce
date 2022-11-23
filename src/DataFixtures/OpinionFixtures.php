<?php

namespace App\DataFixtures;

use App\Entity\Opinion;
use App\DataFixtures\CommentFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OpinionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $note = (new Opinion())
            ->setOpinion(0)
            ->setUser($this->getReference(UserFixtures::USER_REFERENCE))
            ->setComment($this->getReference(CommentFixtures::COMMENT_REFERENCE));

        $manager->persist($note);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CommentFixtures::class,
        ];
    }
}
