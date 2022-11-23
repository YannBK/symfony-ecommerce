<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $address = (new Address())
            ->setUser($this->getReference(UserFixtures::USER_REFERENCE))
            ->setName("nametest")
            ->setFirstname("firstnametest")
            ->setLastname("lastnametest")
            ->setCompany("companytest")
            ->setAddress("addresstest")
            ->setPostal("postaltest")
            ->setCity("citytest")
            ->setCountry("countrytest")
            ->setPhone("phonetest");
        $manager->persist($address);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
