<?php

namespace App\Tests\Repository;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $product = $container->get(ProductRepository::class)->count([]);

        $this->assertEquals(1, $product);
    }
}