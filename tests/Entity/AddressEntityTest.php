<?php

namespace App\Tests\Entity;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddressEntityTest extends KernelTestCase
{
    public function getEntity(): Address
    {
        return (new Address())
            ->setUser(new User())
            ->setName("nametest")
            ->setFirstname("firstnametest")
            ->setLastname("lastnametest")
            ->setCompany("companytest")
            ->setAddress("addresstest")
            ->setPostal("postaltest")
            ->setCity("citytest")
            ->setCountry("countrytest")
            ->setPhone("phonetest");
    }

    public function assertHasErrors(Address $code, int $expected)
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

    public function testValidAddress()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testBlankNameAddress()
    {
        $this->assertHasErrors($this->getEntity()->setName(''), 1);
    }

    public function testBlankFirstameAddress()
    {
        $this->assertHasErrors($this->getEntity()->setFirstname(''), 1);
    }

    public function testBlankLastnameAddress()
    {
        $this->assertHasErrors($this->getEntity()->setLastname(''), 1);
    }

    public function testBlankAddressAddress()
    {
        $this->assertHasErrors($this->getEntity()->setAddress(''), 1);
    }

    public function testBlankPostalAddress()
    {
        $this->assertHasErrors($this->getEntity()->setPostal(''), 1);
    }

    public function testBlankCityAddress()
    {
        $this->assertHasErrors($this->getEntity()->setCity(''), 1);
    }

    public function testBlankCountryAddress()
    {
        $this->assertHasErrors($this->getEntity()->setCountry(''), 1);
    }

    public function testBlankPhoneAddress()
    {
        $this->assertHasErrors($this->getEntity()->setPhone(''), 1);
    }
}
