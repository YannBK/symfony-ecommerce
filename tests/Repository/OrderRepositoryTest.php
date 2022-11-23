<?php

namespace App\Tests\Repository;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $order = $container->get(OrderRepository::class)->count([]);

        $this->assertEquals(1, $order);
    }
}