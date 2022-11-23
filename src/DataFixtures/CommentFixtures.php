<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public const COMMENT_REFERENCE = "comment";

    public function load(ObjectManager $manager): void
    {
        $comment = (new Comment())
            ->setText("texttest")
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setUser($this->getReference(UserFixtures::USER_REFERENCE))
            ->setProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE));

        $manager->persist($comment);
        $manager->flush();

        $this->addReference(self::COMMENT_REFERENCE, $comment);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
        ];
    }
}
