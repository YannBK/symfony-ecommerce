<?php

namespace App\Tests\Repository;

use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddressRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $address = $container->get(AddressRepository::class)->count([]);

        $this->assertEquals(1, $address);
    }
}