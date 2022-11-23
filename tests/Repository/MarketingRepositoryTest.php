<?php

namespace App\Tests\Repository;

use App\Repository\MarketingRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MarketingRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $marketing = $container->get(MarketingRepository::class)->count([]);

        $this->assertEquals(1, $marketing);
    }
}