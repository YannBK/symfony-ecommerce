<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public const PRODUCT_REFERENCE = "product";
    public function load(ObjectManager $manager): void
    {
        $product = (new Product())
            ->setName("nametest")
            ->setSlug("slugtest")
            ->setIllustration("illustrationtest")
            ->setSubtitle("subtitletest")
            ->setDescription("descriptiontest")
            ->setPrice(8)
            ->setIsBest(0);

        $manager->persist($product);
        $manager->flush();

        $this->addReference(self::PRODUCT_REFERENCE, $product);
    }
}
