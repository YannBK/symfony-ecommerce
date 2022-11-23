<?php

namespace App\Tests\Repository;

use App\Repository\CarrierRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CarrierRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $carrier = $container->get(CarrierRepository::class)->count([]);

        $this->assertEquals(1, $carrier);
    }
}