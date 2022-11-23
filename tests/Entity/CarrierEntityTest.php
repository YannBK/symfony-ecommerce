<?php

namespace App\Tests\Entity;

use App\Entity\Carrier;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CarrierEntityTest extends KernelTestCase
{
    public function getEntity(): Carrier
    {
        return (new Carrier())
            ->setName("nametest")
            ->setDescription("descriptiontest")
            ->setPrice(12.50);
    }

    public function assertHasErrors(Carrier $code, int $expected)
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

    public function testValidCarrier()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testBlankNameCarrier()
    {
        $this->assertHasErrors($this->getEntity()->setName(''), 1);
    }

    public function testBlankDescriptionCarrier()
    {
        $this->assertHasErrors($this->getEntity()->setDescription(''), 1);
    }

    public function testBlankPriceCarrier()
    {
        $carrier = (new Carrier())
            ->setName("nametest")
            ->setDescription("descriptiontest");

        $this->assertHasErrors($carrier, 1);
    }

    public function testPriceNumberToStringCarrier()
    {
        $this->assertHasErrors($this->getEntity()->setPrice('12'), 0);
    }

    public function testPriceFloatToStringCarrier()
    {
        $this->assertHasErrors($this->getEntity()->setPrice('12.20'), 0);
    }
}