<?php

namespace App\Tests\Entity;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactEntityTest extends KernelTestCase
{
    public function getFullEntity(): Contact
    {
        return (new Contact())
            ->setFirstname("texttest")
            ->setLastname("texttest")
            ->setEmail("texttest@test.test")
            ->setSubject("texttest")
            ->setText("texttest")
            ->setCreatedAt(new \DateTime("-1 year"));
    }

    public function assertHasErrors(Contact $code, int $expected)
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

    public function testValidContact()
    {
        $this->assertHasErrors($this->getFullEntity(), 0);
    }

    public function testBlankDateContact()
    {
        $contact = (new Contact())
            ->setFirstname("texttest")
            ->setLastname("texttest")
            ->setEmail("texttest@test.test")
            ->setSubject("texttest")
            ->setText("texttest");

        $this->assertHasErrors($contact, 1);
    }

    public function testInvalidEmailContact()
    {
        $this->assertHasErrors($this->getFullEntity()->setEmail('texttest@test'), 1);
        $this->assertHasErrors($this->getFullEntity()->setEmail('texttest@test.'), 1);
        $this->assertHasErrors($this->getFullEntity()->setEmail('texttest@.test'), 1);
        $this->assertHasErrors($this->getFullEntity()->setEmail('texttest.test'), 1);
    }
    
    public function testBlankFirstnameContact()
    {
        $this->assertHasErrors($this->getFullEntity()->setFirstname(''), 1);
    }

    public function testBlankLastnameContact()
    {
        $this->assertHasErrors($this->getFullEntity()->setLastname(''), 1);
    }

    public function testBlankSubjectContact()
    {
        $this->assertHasErrors($this->getFullEntity()->setSubject(''), 1);
    }

    public function testBlankTextContact()
    {
        $this->assertHasErrors($this->getFullEntity()->setText(''), 1);
    }

}
