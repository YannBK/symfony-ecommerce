<?php

namespace App\Tests\Repository;

use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NoteRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $note = $container->get(NoteRepository::class)->count([]);

        $this->assertEquals(1, $note);
    }
}