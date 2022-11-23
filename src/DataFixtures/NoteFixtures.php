<?php

namespace App\DataFixtures;

use App\Entity\Note;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class NoteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $note = (new Note())
            ->setNote(3)
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setUser($this->getReference(UserFixtures::USER_REFERENCE))
            ->setProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE));

        $manager->persist($note);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
        ];
    }
}
