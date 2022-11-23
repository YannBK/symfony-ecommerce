<?php

namespace App\DataFixtures;

use App\Entity\Header;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HeaderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $header = (new Header())
            ->setTitle("titletest")
            ->setContent("contenttest")
            ->setBtnTitle("btntitle")
            ->setBtnUrl("btnUrl")
            ->setIllustration("illustrationtest");
            
        $manager->persist($header);
        $manager->flush();
    }
}
