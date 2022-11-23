<?php

namespace App\Tests\Repository;

use App\Repository\OrderDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderDetailsRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $orderDetails = $container->get(OrderDetailsRepository::class)->count([]);

        $this->assertEquals(1, $orderDetails);
    }
}