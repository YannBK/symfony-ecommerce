<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserEntityTest extends KernelTestCase
{
    public function getEntity(): User
    {
        return (new User())
            ->setEmail("emailtest@test.test")
            ->setRoles(["ROLE_USER"])
            ->setPassword("passwordtest")
            ->setFirstname("firstnametest")
            ->setLastname("lastnametest");
    }

    public function assertHasErrors(User $code, int $expected)
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

    public function testValidUser()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testBlankRolesUser()
    {
        $this->assertHasErrors($this->getEntity()->setRoles([]), 1);
    }

    public function testBlankEmailUser()
    {
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testBlankPasswordUser()
    {
        $this->assertHasErrors($this->getEntity()->setPassword(''), 1);
    }

    public function testBlankFirstnameUser()
    {
        $this->assertHasErrors($this->getEntity()->setFirstname(''), 1);
    }

    public function testBlankLastnameUser()
    {
        $this->assertHasErrors($this->getEntity()->setLastname(''), 1);
    }

    public function testInvaliEmailUser()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('texttest@test'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('texttest@test.'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('texttest@.test'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('texttest.test'), 1);
    }
}
