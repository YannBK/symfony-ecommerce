<?php

namespace App\Tests\Repository;

use App\Repository\ResetPasswordRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResetPasswordRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $resetPassword = $container->get(ResetPasswordRepository::class)->count([]);

        $this->assertEquals(1, $resetPassword);
    }
}