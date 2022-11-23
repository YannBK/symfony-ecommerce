<?php

namespace App\Tests\Entity;

use App\Entity\Note;
use App\Entity\User;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NoteEntityTest extends KernelTestCase
{
    public function getFullEntity(): Note
    {
        return (new Note())
            ->setNote(3)
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setUser(new User())
            ->setProduct(new Product());
    }

    public function assertHasErrors(Note $code, int $expected)
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

    public function testValidNote()
    {
        $this->assertHasErrors($this->getFullEntity(), 0);
    }

    public function testBlankNoteNote()
    {
        $note = (new Note())
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setUser(new User())
            ->setProduct(new Product());
        $this->assertHasErrors($note, 1);
    }

    public function testBlankCreatedAtNote()
    {
        $note = (new Note())
            ->setNote(3)
            ->setUser(new User())
            ->setProduct(new Product());
        $this->assertHasErrors($note, 1);
    }

    public function testNullProductNote()
    {
        $note = (new Note())
            ->setNote(3)
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setUser(new User());
        $this->assertHasErrors($note, 1);
    }
}
