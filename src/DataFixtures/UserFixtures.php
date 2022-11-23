<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail("emailtest@test.test")
            ->setRoles(["ROLE_USER", "ROLE_ADMIN"])
            ->setPassword("passwordtest")
            ->setFirstname("firstnametest")
            ->setLastname("lastnametest");

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::USER_REFERENCE, $user);
    }
}
