<?php

namespace App\Tests\Repository;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $comment = $container->get(CommentRepository::class)->count([]);

        $this->assertEquals(1, $comment);
    }
}