<?php

namespace App\Tests\Entity;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OrderEntityTest extends KernelTestCase
{
    public function getFullEntity(): Order
    {
        return (new Order())
            ->setUser(new User())
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setCarrierName("carriernametest")
            ->setCarrierPrice(5.30)
            ->setDelivery("deliverytest")
            ->setReference("referencetest")
            ->setStripeSessionId(null)
            ->setState(1);
    }

    public function assertHasErrors(Order $code, int $expected)
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

    public function testValidOrder()
    {
        $this->assertHasErrors($this->getFullEntity(), 0);
    }

    public function testNullUserOrder()
    {
        $order = (new Order())
            ->setCreatedAt(new \DateTime("-1 year"))
            ->setCarrierName("carriernametest")
            ->setCarrierPrice(5.30)
            ->setDelivery("deliverytest")
            ->setReference("referencetest")
            ->setStripeSessionId(null)
            ->setState(1);
        $this->assertHasErrors($order, 2);
    }

    public function testBlankDateOrder()
    {
        $order = (new Order())
        ->setUser(new User())
            ->setCarrierName("carriernametest")
            ->setCarrierPrice(5.30)
            ->setDelivery("deliverytest")
            ->setReference("referencetest")
            ->setStripeSessionId(null)
            ->setState(1);
        $this->assertHasErrors($order, 1);
    }

    public function testBlankCarrierNameOrder()
    {
        $this->assertHasErrors($this->getFullEntity()->setCarrierName(''), 1);
    }

    public function testBlankCarrierPriceOrder()
    {
        $order = (new Order())
        ->setUser(new User())
        ->setCreatedAt(new \DateTime("-1 year"))
        ->setCarrierName("carriernametest")
        ->setDelivery("deliverytest")
        ->setReference("referencetest")
        ->setStripeSessionId(null)
        ->setState(1);
        $this->assertHasErrors($order, 1);
    }

    public function testBlankDeliveryOrder()
    {
        $this->assertHasErrors($this->getFullEntity()->setDelivery(''), 1);
    }

    public function testBlankReferenceOrder()
    {
        $this->assertHasErrors($this->getFullEntity()->setReference(''), 1);
    }

    public function testBlankStateOrder()
    {
        $order = (new Order())
        ->setUser(new User())
        ->setCreatedAt(new \DateTime("-1 year"))
        ->setCarrierName("carriernametest")
        ->setCarrierPrice(5.30)
        ->setDelivery("deliverytest")
        ->setReference("referencetest")
        ->setStripeSessionId(null);
        $this->assertHasErrors($order, 1);
    }
}
