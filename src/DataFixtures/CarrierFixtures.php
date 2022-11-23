<?php

namespace App\DataFixtures;

use App\Entity\Carrier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarrierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $carrier = (new Carrier())
            ->setName("nametest")
            ->setDescription("descriptiontest")
            ->setPrice(5);

        $manager->persist($carrier);
        $manager->flush();
    }
}
