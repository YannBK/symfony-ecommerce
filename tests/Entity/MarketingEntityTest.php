<?php

namespace App\Tests\Entity;

use App\Entity\Marketing;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MarketingEntityTest extends KernelTestCase
{
    public function getEntity(): Marketing
    {
        return (new Marketing())
            ->setTitle("nametest")
            ->setSubtitle("firstnametest")
            ->setContent("lastnametest")
            ->setPlace(1)
            ->setIllustration("marketingtest")
            ->setImageSide("postaltest");
    }

    public function assertHasErrors(Marketing $code, int $expected)
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

    public function testValidMarketing()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testBlankTitleMarketing()
    {
        $this->assertHasErrors($this->getEntity()->setTitle(''), 1);
    }

    public function testBlankSubtitleMarketing()
    {
        $this->assertHasErrors($this->getEntity()->setSubtitle(''), 1);
    }

    public function testBlankContentMarketing()
    {
        $this->assertHasErrors($this->getEntity()->setContent(''), 1);
    }

    public function testBlankIllustrationMarketing()
    {
        $this->assertHasErrors($this->getEntity()->setIllustration(''), 1);
    }

    public function testBlankImageSideMarketing()
    {
        $this->assertHasErrors($this->getEntity()->setImageSide(''), 1);
    }
}
