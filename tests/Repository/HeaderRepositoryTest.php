<?php

namespace App\Tests\Repository;

use App\Repository\HeaderRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HeaderRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $header = $container->get(HeaderRepository::class)->count([]);

        $this->assertEquals(1, $header);
    }
}