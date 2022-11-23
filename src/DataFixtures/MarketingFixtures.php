<?php

namespace App\DataFixtures;

use App\Entity\Marketing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MarketingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $marketing = (new Marketing())
            ->setTitle("titletest")
            ->setSubtitle("subtitletest")
            ->setContent("contenttest")
            ->setPlace(2)
            ->setIllustration("illustrationtest")
            ->setImageSide("left");

        $manager->persist($marketing);
        $manager->flush();
    }
}
