<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentEntityTest extends KernelTestCase
{
    public function getEntity(): Comment
    {
        return (new Comment())
            ->setText("texttest");
    }

    public function getFullEntity(): Comment
    {
        return (new Comment())
            ->setText("texttest")
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setUser(new User())
            ->setProduct(new Product());
    }

    public function assertHasErrors(Comment $code, int $expected)
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

    public function testValidComment()
    {
        $comment = $this->getEntity()
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setUser(new User())
            ->setProduct(new Product());

        $this->assertHasErrors($comment, 0);
    }

    public function testNullUserComment()
    {
        $comment = $this->getEntity()
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setProduct(new Product());

        $this->assertHasErrors($comment, 1);
    }

    public function testNullProductComment()
    {
        $comment = $this->getEntity()
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setUser(new User());

        $this->assertHasErrors($comment, 1);
    }

    public function testBlankTextComment()
    {
        $this->assertHasErrors($this->getFullEntity()->setText(''), 1);
    }

    public function testBlankCreatedAtComment()
    {
        $comment = $this->getEntity()
            ->setUser(new User())
            ->setProduct(new Product());
        
        $this->assertHasErrors($comment, 1);
    }
}
