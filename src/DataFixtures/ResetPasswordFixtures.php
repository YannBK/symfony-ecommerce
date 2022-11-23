<?php

namespace App\DataFixtures;

use App\Entity\ResetPassword;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResetPasswordFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $note = (new ResetPassword())
            ->setUser($this->getReference(UserFixtures::USER_REFERENCE))
            ->setToken("tokentest")
            ->setCreatedAt(new \DateTime("-1 year"));

        $manager->persist($note);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
