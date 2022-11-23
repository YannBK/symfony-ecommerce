<?php

namespace App\DataFixtures;

use App\Entity\Order;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORDER_REFERENCE = "order";

    public function load(ObjectManager $manager): void
    {
        $order = (new Order())
            ->setUser($this->getReference(UserFixtures::USER_REFERENCE))
            ->setCreatedAt(new \DateTime('-1 year'))
            ->setCarrierName("carriernametest")
            ->setCarrierPrice(5)
            ->setDelivery("deliverytest")
            ->setReference("referencetest")
            ->setState(0);

        $manager->persist($order);
        $manager->flush();

        $this->addReference(self::ORDER_REFERENCE, $order);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
