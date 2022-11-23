<?php

namespace App\Tests\Entity;

use App\Entity\Header;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HeaderEntityTest extends KernelTestCase
{
    public function getEntity(): Header
    {
        return (new Header())
            ->setTitle("nametest")
            ->setContent("firstnametest")
            ->setBtnTitle("lastnametest")
            ->setBtnUrl("companytest")
            ->setIllustration("headertest");
    }

    public function assertHasErrors(Header $code, int $expected)
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

    public function testValidHeader()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testBlankTitleHeader()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }

    public function testBlankContentHeader()
    {
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }

    public function testBlankBtnTitleHeader()
    {
        $this->assertHasErrors($this->getEntity()->setBtnTitle(''), 1);
    }

    public function testBlankBtnUrlHeader()
    {
        $this->assertHasErrors($this->getEntity()->setBtnUrl(''), 1);
    }

    public function testBlankIllustrationHeader()
    {
        $this->assertHasErrors($this->getEntity()->setIllustration(''), 1);
    }
}
