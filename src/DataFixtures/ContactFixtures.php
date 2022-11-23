<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $contact = (new Contact())
            ->setFirstname("firstnametest")
            ->setLastname("lastnametest")
            ->setEmail("email@test.test")
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setSubject("subjecttest")
            ->setText("texttest")
            ->setUser($this->getReference(UserFixtures::USER_REFERENCE));
        $manager->persist($contact);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
