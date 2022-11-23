<?php

namespace App\Tests\Repository;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactRepositoryTest extends KernelTestCase
{
    public function testCount() {
        self::bootKernel();
        $container = static::getContainer();
        $contact = $container->get(ContactRepository::class)->count([]);

        $this->assertEquals(1, $contact);
    }
}