<?php

namespace App\Tests\Repository;

use App\Repository\OpinionRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OpinionRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $opinion = $container->get(OpinionRepository::class)->count([]);

        $this->assertEquals(1, $opinion);
    }
}