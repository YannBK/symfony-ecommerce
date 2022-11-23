<?php

namespace App\Tests\Entity;

use App\Entity\Opinion;
use App\Entity\User;
use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OpinionEntityTest extends KernelTestCase
{
    public function assertHasErrors(Opinion $code, int $expected)
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

    public function testValidOpinion()
    {
        $opinion = (new Opinion())
            ->setUser(new User())
            ->setComment(new Comment())
            ->setOpinion(0);
        $this->assertHasErrors($opinion, 0);
    }

    public function testNullUserOpinion()
    {
        $opinion = (new Opinion())
            ->setComment(new Comment())
            ->setOpinion(0);
        $this->assertHasErrors($opinion, 2);//not blank AND not null
    }

    public function testNullCommentOpinion()
    {
        $opinion = (new Opinion())
            ->setUser(new User())
            ->setOpinion(0);
        $this->assertHasErrors($opinion, 2);
    }
}
