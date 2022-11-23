<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoryEntityTest extends KernelTestCase
{
    public function getEntity(): Category
    {
        return (new Category())
            ->setName("nametest");
    }

    public function assertHasErrors(Category $code, int $expected)
    {
        self::bootKernel();
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($code);
        $messages = [];

        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' ==> ' . $error->getMessage();
        }

        $this->assertEquals($expected, count($errors), implode(', ', $messages));
    }

    public function testValidCategory()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testBlankNameCategory()
    {
        $this->assertHasErrors($this->getEntity()->setName(''), 1);
    }
}
