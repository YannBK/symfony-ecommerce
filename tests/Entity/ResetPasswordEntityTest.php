<?php

namespace App\Tests\Entity;

use App\Entity\ResetPassword;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ResetPasswordEntityTest extends KernelTestCase
{
    public function getEntity(): ResetPassword
    {
        return (new ResetPassword())
            ->setUser(null)
            ->setToken("tokentest");
    }

    public function assertHasErrors(ResetPassword $code, int $expected)
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

    public function testValidResetPassword()
    {
        $this->assertHasErrors($this->getEntity()->setCreatedAt(new \DateTime("-1 year")), 0);
    }

    public function testBlankCreatedAtResetPassword()
    {
        $this->assertHasErrors($this->getEntity(), 1);
    }

    public function testBlankTokenResetPassword()
    {
        $this->assertHasErrors($this->getEntity()->setToken('')->setCreatedAt(new \DateTime("-1 year")), 1);
    }
}
