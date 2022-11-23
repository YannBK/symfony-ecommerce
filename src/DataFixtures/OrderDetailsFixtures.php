<?php

namespace App\DataFixtures;

use App\Entity\OrderDetails;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\OrderFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderDetailsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $orderDetails = (new OrderDetails())
            ->setMyOrder($this->getReference(OrderFixtures::ORDER_REFERENCE))
            ->setProductName("Productnametest")
            ->setQuantity(2)
            ->setPrice(2)
            ->setTotal(2)
            ->setProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE));

        $manager->persist($orderDetails);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            OrderFixtures::class,
            ProductFixtures::class,
        ];
    }
}
